<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Create a new folder
     */
    public function createFolder(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
                'parent_folder_id' => 'nullable|exists:document_folders,id',
                'description' => 'nullable|string|max:500',
            ]);

            $internId = $request->session()->get('intern_id');
            if (!$internId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            // Verify parent folder belongs to this intern if provided
            if ($request->parent_folder_id) {
                $parentFolder = DocumentFolder::where('id', $request->parent_folder_id)
                    ->where('intern_id', $internId)
                    ->first();

                if (!$parentFolder) {
                    return response()->json(['success' => false, 'message' => 'Parent folder not found'], 404);
                }
            }

            $folder = DocumentFolder::create([
                'intern_id' => $internId,
                'name' => $request->name,
                'color' => $request->color ?? '#3B82F6',
                'description' => $request->description,
                'parent_folder_id' => $request->parent_folder_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Folder created successfully',
                'folder' => $folder,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->errors()['name'] ?? ['Unknown error'])
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating folder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload document
     */
    public function uploadDocument(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png,gif,zip,rar,ppt,pptx,csv', // 50MB limit
                'folder_id' => 'nullable|exists:document_folders,id',
                'description' => 'nullable|string|max:500',
            ]);

            $internId = $request->session()->get('intern_id');
            if (!$internId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            // Verify folder belongs to this intern if provided
            if ($request->folder_id) {
                $folder = DocumentFolder::where('id', $request->folder_id)
                    ->where('intern_id', $internId)
                    ->first();

                if (!$folder) {
                    return response()->json(['success' => false, 'message' => 'Folder not found'], 404);
                }
            }

            $file = $request->file('file');

            // Sanitize filename - remove spaces and special characters
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nameWithoutExt);
            $filename = time() . '_' . $sanitizedName . '.' . $extension;

            // Ensure directory exists (in private folder)
            $directory = "documents" . DIRECTORY_SEPARATOR . $internId;
            Storage::disk('local')->makeDirectory($directory);

            // Store file in storage/app/private/documents/{intern_id}
            $path = Storage::disk('local')->putFileAs($directory, $file, $filename);

            if (!$path) {
                return response()->json(['success' => false, 'message' => 'Failed to store file'], 500);
            }

            // Get file size using Storage facade to avoid path issues
            $fileSize = $this->formatBytes(Storage::disk('local')->size($path));

            $document = Document::create([
                'intern_id' => $internId,
                'folder_id' => $request->folder_id,
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $fileSize,
                'file_type' => $file->getMimeType(),
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'document' => $document,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading document: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all folders and documents for intern
     */
    public function getFolders(Request $request)
    {
        try {
            $internId = $request->session()->get('intern_id');
            if (!$internId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $folders = DocumentFolder::where('intern_id', $internId)
                ->whereNull('parent_folder_id')
                ->with(['documents', 'childFolders'])
                ->get();

            $documentsWithoutFolder = Document::where('intern_id', $internId)
                ->whereNull('folder_id')
                ->get();

            return response()->json([
                'success' => true,
                'folders' => $folders,
                'documents' => $documentsWithoutFolder,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading documents: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get folder contents
     */
    public function getFolderContents($folderId, Request $request)
    {
        $internId = $request->session()->get('intern_id');
        if (!$internId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $folder = DocumentFolder::where('intern_id', $internId)->find($folderId);
        if (!$folder) {
            return response()->json(['success' => false, 'message' => 'Folder not found'], 404);
        }

        $childFolders = $folder->childFolders()->get();
        $documents = $folder->documents()->get();

        return response()->json([
            'success' => true,
            'folder' => $folder,
            'folders' => $childFolders,
            'documents' => $documents,
        ]);
    }

    /**
     * Update folder
     */
    public function updateFolder(Request $request, $folderId)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'color' => 'sometimes|nullable|string|regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
            'description' => 'sometimes|nullable|string|max:500',
        ]);

        $internId = $request->session()->get('intern_id');
        if (!$internId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $folder = DocumentFolder::where('intern_id', $internId)->find($folderId);
        if (!$folder) {
            return response()->json(['success' => false, 'message' => 'Folder not found'], 404);
        }

        $folder->update($request->only(['name', 'color', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Folder updated successfully',
            'folder' => $folder,
        ]);
    }

    /**
     * Delete folder
     */
    public function deleteFolder($folderId, Request $request)
    {
        $internId = $request->session()->get('intern_id');
        if (!$internId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $folder = DocumentFolder::where('intern_id', $internId)->find($folderId);
        if (!$folder) {
            return response()->json(['success' => false, 'message' => 'Folder not found'], 404);
        }

        // Soft delete will handle the folder and its relations
        $folder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Folder deleted successfully',
        ]);
    }

    /**
     * Delete document
     */
    public function deleteDocument($documentId, Request $request)
    {
        $internId = $request->session()->get('intern_id');
        if (!$internId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $document = Document::where('intern_id', $internId)->find($documentId);
        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        // Delete file from storage
        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully',
        ]);
    }

    /**
     * Download document
     */
    public function downloadDocument($documentId, Request $request)
    {
        $internId = $request->session()->get('intern_id');
        if (!$internId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $document = Document::where('intern_id', $internId)->find($documentId);
        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Document not found'], 404);
        }

        // Check if file exists
        if (!Storage::disk('local')->exists($document->file_path)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        // Get file content and return as download
        return response()->download(
            Storage::disk('local')->path($document->file_path),
            $document->name
        );
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
