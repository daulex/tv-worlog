<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    use AuthorizesRequests;

    public function download($fileId)
    {
        $file = File::findOrFail($fileId);

        $this->authorize('download', $file);

        if (! Storage::disk('public')->exists($file->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($file->file_path, $file->filename);
    }
}
