<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function agency()
    {
        return view('portals.agency');
    }

    public function downloadDocument($filename)
    {
        $path = storage_path('app/public/tasks/documents/' . basename($filename));

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function paymentProof(Request $request)
    {
        $path = $request->query('path', '');
        if (!$path) abort(404);
        // Prevent directory traversal
        $path = ltrim(str_replace(['..', '//'], '', $path), '/');
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath)) abort(404, 'File not found');
        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
        ]);
    }
}
