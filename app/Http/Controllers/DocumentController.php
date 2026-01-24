<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Determine if the current authenticated user can access Digital Records.
     */
    private function authorizeDigitalRecords(bool $requireEdit = false): bool
    {
        if (!Auth::check()) {
            return false;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admins/super admins have full access
        if ($user->isAdmin()) {
            return true;
        }

        // Team Leaders must have module permission
        if (!$user->isTeamLeader()) {
            return false;
        }

        if ($requireEdit) {
            return $user->canEditModule('digital_records');
        }

        return $user->canViewModule('digital_records');
    }

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

            // Get intern info for folder organization
            $intern = Intern::find($internId);
            if (!$intern) {
                return response()->json(['success' => false, 'message' => 'Intern not found'], 404);
            }

            // Interns can only create folders inside shared folders created by admin
            if (!$request->parent_folder_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only create folders inside shared folders created by the administrator'
                ], 403);
            }

            // Verify parent folder - must be a shared folder (root or subfolder)
            $parentFolder = DocumentFolder::where('folder_type', 'shared')
                ->find($request->parent_folder_id);

            if (!$parentFolder) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parent folder not found or you do not have permission to create folders here'
                ], 404);
            }

            // Check permission: if parent is root shared folder, verify intern access
            if (!$parentFolder->parent_folder_id && !in_array('intern', $parentFolder->allowed_users ?? [])) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to create folders here'
                ], 403);
            }

            // Create physical directory in storage using parent's storage path
            $baseDirectory = $parentFolder->storage_path;
            $folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->name);
            $folderPath = $baseDirectory . '/' . $folderName;

            // Create the physical directory
            Storage::disk('local')->makeDirectory($folderPath);

            $folder = DocumentFolder::create([
                'intern_id' => null,
                'name' => $request->name,
                'color' => $request->color ?? '#3B82F6',
                'description' => $request->description,
                'parent_folder_id' => $request->parent_folder_id,
                'folder_type' => 'shared',
                'storage_path' => $folderPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Folder created successfully',
                'data' => $folder
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

            // Get intern info for folder organization
            $intern = Intern::find($internId);
            if (!$intern) {
                return response()->json(['success' => false, 'message' => 'Intern not found'], 404);
            }

// Interns can only upload to shared folders
            if (!$request->folder_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must select a folder to upload to. Please choose a shared folder created by the administrator.'
                ], 403);
            }

            // Determine the storage directory
            $folder = DocumentFolder::where('folder_type', 'shared')
                ->find($request->folder_id);

            if (!$folder || !$folder->storage_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Folder not found or you do not have permission to upload here'
                ], 404);
            }

            // Check permission: either root shared folder with intern access OR subfolder of shared folder
            if (!$folder->parent_folder_id) {
                // Root folder - check allowed_users
                if (!in_array('intern', $folder->allowed_users ?? [])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have permission to upload to this folder'
                    ], 403);
                }
            }

            $directory = $folder->storage_path;

            // Normalize to forward slashes for consistency
            $directory = str_replace('\\', '/', $directory);

            Log::info('Uploading file to folder', [
                'folder_id' => $folder->id,
                'folder_name' => $folder->name,
                'storage_path' => $directory,
                'folder_exists' => Storage::disk('local')->exists($directory)
            ]);

            // Ensure directory exists
            Storage::disk('local')->makeDirectory($directory);

            $file = $request->file('file');

            // Sanitize filename - remove spaces and special characters
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
            $sanitizedName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nameWithoutExt);
            $filename = time() . '_' . $sanitizedName . '.' . $extension;

            // Store file
            $path = Storage::disk('local')->putFileAs($directory, $file, $filename);

            if (!$path) {
                return response()->json(['success' => false, 'message' => 'Failed to store file'], 500);
            }

            Log::info('File uploaded successfully', [
                'filename' => $filename,
                'storage_path' => $path,
                'full_path' => Storage::disk('local')->path($path),
                'file_exists' => Storage::disk('local')->exists($path)
            ]);

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

        // Try to find folder - either owned by intern or shared with intern
        $folder = DocumentFolder::where(function($query) use ($internId) {
            $query->where('intern_id', $internId)
                  ->orWhere('folder_type', 'shared');
        })->find($folderId);

        if (!$folder) {
            return response()->json(['success' => false, 'message' => 'Folder not found'], 404);
        }

        // Check if folder has permission for intern (root shared folders or subfolders of shared folders)
        if ($folder->folder_type === 'shared') {
            // For root shared folders, check allowed_users
            if (!$folder->parent_folder_id && !in_array('intern', $folder->allowed_users ?? [])) {
                return response()->json(['success' => false, 'message' => 'No permission to access this folder'], 403);
            }
        }

        // For shared folders, get files from storage path
        if ($folder->folder_type === 'shared' && $folder->storage_path) {
            $storagePath = $folder->storage_path;
            $files = [];
            $subfolders = [];

            if (Storage::disk('local')->exists($storagePath)) {
                // Get subfolders from database (created by interns)
                $dbSubfolders = DocumentFolder::where('parent_folder_id', $folderId)->get();
                foreach ($dbSubfolders as $subfolder) {
                    $subfolderStoragePath = str_replace('\\', '/', (string) $subfolder->storage_path);
                    $subfolderItemCount = 0;
                    if ($subfolderStoragePath !== '' && Storage::disk('local')->exists($subfolderStoragePath)) {
                        // Count both files and subdirectories
                        $filesCount = count(Storage::disk('local')->files($subfolderStoragePath));
                        $dirsCount = count(Storage::disk('local')->directories($subfolderStoragePath));
                        // Also count subfolders from database
                        $dbNestedSubfoldersCount = DocumentFolder::where('parent_folder_id', $subfolder->id)->count();
                        $subfolderItemCount = $filesCount + max($dirsCount, $dbNestedSubfoldersCount);
                    }

                    $subfolders[] = [
                        'id' => $subfolder->id,
                        'name' => $subfolder->name,
                        'color' => $subfolder->color,
                        'description' => $subfolder->description,
                        'folder_type' => $subfolder->folder_type,
                        'item_count' => $subfolderItemCount,
                    ];
                }

                // Get files
                $filesList = Storage::disk('local')->files($storagePath);
                foreach ($filesList as $file) {
                    $fileName = basename($file);
                    $size = Storage::disk('local')->size($file);
                    $lastModified = Storage::disk('local')->lastModified($file);

                    $files[] = [
                        'name' => $fileName,
                        'path' => $file,
                        'size' => $size,
                        'file_size' => $this->formatBytes($size),
                        'file_type' => pathinfo($fileName, PATHINFO_EXTENSION),
                        'created_at' => date('Y-m-d H:i:s', $lastModified),
                        'uploaded_at' => date('Y-m-d H:i:s', $lastModified),
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'folder' => $folder,
                'folders' => $subfolders,
                'documents' => $files,
            ]);
        }

        // For intern's own folders, get from database
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
     * Download document
     */
    public function downloadDocument($documentId, Request $request)
    {
        $internId = $request->session()->get('intern_id');
        if (!$internId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Try to find document in database first
        $document = Document::where('intern_id', $internId)->find($documentId);

        if ($document) {
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

        // If not in database, treat documentId as a file path (for shared folder files)
        // Decode the path that was passed as ID
        $filePath = base64_decode($documentId);

        // Verify the file is in a shared folder the intern has access to
        if (strpos($filePath, 'Shared') !== 0) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        if (!Storage::disk('local')->exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        $fileName = basename($filePath);
        return response()->download(
            Storage::disk('local')->path($filePath),
            $fileName
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

    /**
     * Admin: Get all folders in storage
     */
    public function getAdminFolders(Request $request)
    {
        try {
            if (!$this->authorizeDigitalRecords(false)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $folders = [];

            // Get shared folders from database
            $sharedFolders = DocumentFolder::where('folder_type', 'shared')
                ->whereNull('parent_folder_id')
                ->get();

            foreach ($sharedFolders as $folder) {
                $itemCount = 0;
                $storagePath = $folder->storage_path ?? ('Shared/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $folder->name));

                if (Storage::disk('local')->exists($storagePath)) {
                    // Count all files recursively
                    $allFiles = Storage::disk('local')->allFiles($storagePath);
                    $itemCount = count($allFiles);
                }

                $folders[] = [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'path' => $storagePath,
                    'item_count' => $itemCount,
                    'is_folder' => true,
                    'folder_type' => 'shared',
                    'color' => $folder->color,
                ];
            }

            // Get Internship folder
            $internshipPath = 'Internship';
            if (Storage::disk('local')->exists($internshipPath)) {
                $directories = Storage::disk('local')->directories($internshipPath);

                foreach ($directories as $dir) {
                    $folderName = basename($dir);
                    $files = Storage::disk('local')->allFiles($dir);

                    $folders[] = [
                        'name' => $folderName,
                        'path' => $dir,
                        'item_count' => count($files),
                        'is_folder' => true,
                        'folder_type' => 'intern',
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'folders' => $folders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading folders: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getAdminFolderContents(Request $request)
    {
        try {
            if (!$this->authorizeDigitalRecords(false)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $path = $request->query('path', '');

            // Normalize path to use forward slashes
            $path = str_replace('\\', '/', $path);

            Log::info('Admin loading folder contents', [
                'original_path' => $request->query('path', ''),
                'normalized_path' => $path,
                'exists' => Storage::disk('local')->exists($path),
                'full_storage_path' => Storage::disk('local')->path($path)
            ]);

            // Check if this path corresponds to a database folder
            $dbFolder = DocumentFolder::where('storage_path', $path)
                ->orWhere('storage_path', str_replace('/', '\\', $path))
                ->first();

            if ($dbFolder) {
                Log::info('Found database folder', [
                    'id' => $dbFolder->id,
                    'name' => $dbFolder->name,
                    'type' => $dbFolder->folder_type,
                    'db_storage_path' => $dbFolder->storage_path
                ]);
            } else {
                Log::info('No database folder found for path', ['path' => $path]);
            }

            // Get subdirectories from filesystem
            $directories = Storage::disk('local')->directories($path);
            $files = Storage::disk('local')->files($path);

            // Also try to list all files recursively to see if files exist deeper
            $allFiles = Storage::disk('local')->allFiles($path);

            Log::info('Storage scan results', [
                'path' => $path,
                'directories' => $directories,
                'files_in_root' => $files,
                'all_files_recursive' => $allFiles,
                'file_count' => count($files),
                'all_files_count' => count($allFiles)
            ]);

            $items = [];

            // If this is a shared folder, also get subfolders from database
            if ($dbFolder && $dbFolder->folder_type === 'shared') {
                $dbSubfolders = DocumentFolder::where('parent_folder_id', $dbFolder->id)->get();

                // Add database-tracked subfolders
                foreach ($dbSubfolders as $subfolder) {
                    if ($subfolder->storage_path && Storage::disk('local')->exists($subfolder->storage_path)) {
                        $subFiles = Storage::disk('local')->allFiles($subfolder->storage_path);

                        $items[] = [
                            'id' => $subfolder->id,
                            'name' => $subfolder->name,
                            'path' => $subfolder->storage_path,
                            'type' => 'folder',
                            'item_count' => count($subFiles),
                            'is_folder' => true,
                            'color' => $subfolder->color,
                        ];
                    }
                }
            } else {
                // Add filesystem folders (for non-database folders)
                foreach ($directories as $dir) {
                    $folderName = basename($dir);
                    $subFiles = Storage::disk('local')->allFiles($dir);

                    $items[] = [
                        'name' => $folderName,
                        'path' => $dir,
                        'type' => 'folder',
                        'item_count' => count($subFiles),
                        'is_folder' => true,
                    ];
                }
            }

            // Add files (for all folder types)
            foreach ($files as $file) {
                $fileName = basename($file);
                $size = Storage::disk('local')->size($file);
                $lastModified = Storage::disk('local')->lastModified($file);

                // Get mime type from file extension
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                $mimeTypes = [
                    'pdf' => 'application/pdf',
                    'doc' => 'application/msword',
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'xls' => 'application/vnd.ms-excel',
                    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ];
                $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

                $items[] = [
                    'name' => $fileName,
                    'path' => $file,
                    'type' => 'file',
                    'size' => $this->formatBytes($size),
                    'size_bytes' => $size,
                    'modified' => date('M d, Y', $lastModified),
                    'modified_timestamp' => $lastModified,
                    'mime_type' => $mimeType,
                    'is_folder' => false,
                ];
            }

            return response()->json([
                'success' => true,
                'items' => $items,
                'current_path' => $path,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading folder contents: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Download file
     */
    public function adminDownloadFile(Request $request)
    {
        try {
            if (!$this->authorizeDigitalRecords(false)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $path = $request->query('path');

            if (!$path || !Storage::disk('local')->exists($path)) {
                return response()->json(['success' => false, 'message' => 'File not found'], 404);
            }

            $fileName = basename($path);

            return response()->download(
                Storage::disk('local')->path($path),
                $fileName
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error downloading file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Create shared folder with permissions
     */
    public function adminCreateFolder(Request $request)
    {
        try {
            if (!$this->authorizeDigitalRecords(true)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
                'description' => 'nullable|string|max:500',
                'allowed_users' => 'required|array',
                'allowed_users.*' => 'in:intern,team_leader,startup',
            ]);

            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $adminId = Auth::id();

            // Create physical directory in storage
            $folderName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->name);
            $storagePath = "Shared/" . $folderName;

            Storage::disk('local')->makeDirectory($storagePath);

            $folder = DocumentFolder::create([
                'name' => $request->name,
                'color' => $request->color ?? '#3B82F6',
                'description' => $request->description,
                'created_by_admin' => $adminId,
                'folder_type' => 'shared',
                'allowed_users' => $request->allowed_users,
                'storage_path' => $storagePath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shared folder created successfully',
                'folder' => $folder,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating folder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get folders accessible by current user (intern/team leader)
     */
    public function getAccessibleFolders(Request $request)
    {
        try {
            $internId = $request->session()->get('intern_id');
            if (!$internId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            // Get shared folders the user has access to
            $sharedFolders = DocumentFolder::where('folder_type', 'shared')
                ->whereNull('parent_folder_id')
                ->whereJsonContains('allowed_users', 'intern')
                ->get();

            // Add upload counts for each shared folder (files + subdirectories inside the folder)
            $sharedFolders->transform(function (DocumentFolder $folder) {
                $storagePath = str_replace('\\', '/', (string) $folder->storage_path);

                $itemCount = 0;
                if ($storagePath !== '' && Storage::disk('local')->exists($storagePath)) {
                    // Count both files and directories in the folder
                    $filesCount = count(Storage::disk('local')->files($storagePath));
                    $dirsCount = count(Storage::disk('local')->directories($storagePath));
                    // Also count subfolders from database
                    $dbSubfoldersCount = DocumentFolder::where('parent_folder_id', $folder->id)->count();
                    $itemCount = $filesCount + max($dirsCount, $dbSubfoldersCount);
                }

                $folder->setAttribute('item_count', $itemCount);
                return $folder;
            });

            return response()->json([
                'success' => true,
                'folders' => $sharedFolders,
                'documents' => [], // No root-level documents for interns
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading folders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Get all folders including shared ones
     */
    public function adminGetAllFolders(Request $request)
    {
        try {
            if (!$this->authorizeDigitalRecords(false)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Get admin-created shared folders
            $sharedFoldersDb = DocumentFolder::where('folder_type', 'shared')
                ->whereNull('parent_folder_id')
                ->get();

            $sharedFolders = [];
            foreach ($sharedFoldersDb as $folder) {
                $storagePath = $folder->storage_path ?? ('Shared/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $folder->name));
                $itemCount = 0;

                if (Storage::disk('local')->exists($storagePath)) {
                    $itemCount = count(Storage::disk('local')->allFiles($storagePath));
                }

                $sharedFolders[] = [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'path' => $storagePath,
                    'storage_path' => $storagePath,
                    'item_count' => $itemCount,
                    'is_folder' => true,
                    'folder_type' => 'shared',
                    'color' => $folder->color,
                    'description' => $folder->description,
                ];
            }

            // Get all intern folders from filesystem
            $internshipPath = 'Internship';
            $directories = [];

            if (Storage::disk('local')->exists($internshipPath)) {
                $directories = Storage::disk('local')->directories($internshipPath);
            }

            $internFolders = [];
            foreach ($directories as $dir) {
                $folderName = basename($dir);
                $files = Storage::disk('local')->allFiles($dir);

                $internFolders[] = [
                    'name' => $folderName,
                    'path' => $dir,
                    'item_count' => count($files),
                    'is_folder' => true,
                    'folder_type' => 'intern',
                ];
            }

            return response()->json([
                'success' => true,
                'shared_folders' => $sharedFolders,
                'intern_folders' => $internFolders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading folders: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Admin: Digital records statistics
     */
    public function adminGetStats(Request $request)
    {
        try {
            if (!$this->authorizeDigitalRecords(false)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Count database-tracked shared folders
            $sharedFolderCount = DocumentFolder::where('folder_type', 'shared')->count();

            // Count internship folders from filesystem
            $internshipPath = 'Internship';
            $internFolders = Storage::disk('local')->exists($internshipPath)
                ? Storage::disk('local')->directories($internshipPath)
                : [];

            $totalFolders = $sharedFolderCount + count($internFolders);

            // Count files and size across Shared and Internship roots
            $roots = ['Shared', 'Internship'];
            $fileCount = 0;
            $totalBytes = 0;
            $recentUploads = 0;
            $recentThreshold = now()->subDays(7)->timestamp;

            foreach ($roots as $root) {
                if (!Storage::disk('local')->exists($root)) {
                    continue;
                }

                $files = Storage::disk('local')->allFiles($root);
                $fileCount += count($files);

                foreach ($files as $file) {
                    $totalBytes += Storage::disk('local')->size($file);
                    $lastModified = Storage::disk('local')->lastModified($file);
                    if ($lastModified >= $recentThreshold) {
                        $recentUploads++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'folders' => $totalFolders,
                'files' => $fileCount,
                'storage_bytes' => $totalBytes,
                'recent_uploads' => $recentUploads,
                'storage_human' => $this->formatBytes($totalBytes),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading stats: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a file (Admin only)
     */
    public function deleteFile(Request $request)
    {
        try {
            if (!$this->authorizeDigitalRecords(true)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $request->validate([
                'path' => 'required|string',
            ]);

            $path = str_replace('\\', '/', $request->path);

            // Check if file exists
            if (!Storage::disk('local')->exists($path)) {
                return response()->json(['success' => false, 'message' => 'File not found'], 404);
            }

            // Delete the file
            Storage::disk('local')->delete($path);

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a folder (Admin only)
     */
    public function deleteFolder(Request $request)
    {
        try {
            if (!$this->authorizeDigitalRecords(true)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $request->validate([
                'folder_id' => 'required|exists:document_folders,id',
            ]);

            $folder = DocumentFolder::findOrFail($request->folder_id);

            // Delete all files in the folder from storage
            if ($folder->storage_path) {
                $path = str_replace('\\', '/', $folder->storage_path);
                if (Storage::disk('local')->exists($path)) {
                    Storage::disk('local')->deleteDirectory($path);
                }
            }

            // Delete all child folders recursively
            $this->deleteChildFolders($folder->id);

            // Delete the folder from database
            $folder->delete();

            return response()->json([
                'success' => true,
                'message' => 'Folder deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting folder: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to delete child folders recursively
     */
    private function deleteChildFolders($parentId)
    {
        $childFolders = DocumentFolder::where('parent_folder_id', $parentId)->get();

        foreach ($childFolders as $child) {
            // Recursively delete children
            $this->deleteChildFolders($child->id);

            // Delete storage
            if ($child->storage_path) {
                $path = str_replace('\\', '/', $child->storage_path);
                if (Storage::disk('local')->exists($path)) {
                    Storage::disk('local')->deleteDirectory($path);
                }
            }

            // Delete from database
            $child->delete();
        }
    }
}
