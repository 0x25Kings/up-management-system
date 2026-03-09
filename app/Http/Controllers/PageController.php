<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $path = 'tasks/documents/' . basename($filename);

        if (!Storage::disk(config('filesystems.upload_disk'))->exists($path)) {
            abort(404);
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk(config('filesystems.upload_disk'));
        return $disk->download($path);
    }

    public function paymentProof(Request $request)
    {
        $path = $request->query('path', '');
        if (!$path) abort(404);
        // Prevent directory traversal
        $path = ltrim(str_replace(['..', '//'], '', $path), '/');

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk(config('filesystems.upload_disk'));
        if (!$disk->exists($path)) {
            abort(404, 'File not found');
        }

        return $disk->response($path);
    }
}
