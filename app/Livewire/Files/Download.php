<?php

namespace App\Livewire\Files;

use App\Models\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Download extends Component
{
    use AuthorizesRequests;

    public File $file;

    public function mount(File $file)
    {
        $this->authorize('download', $file);
        $this->file = $file;
    }

    public function download()
    {
        $this->authorize('download', $this->file);

        if (! Storage::disk('public')->exists($this->file->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($this->file->file_path, $this->file->filename);
    }

    public function render()
    {
        // This component should not render, it should trigger download directly
        abort(404);
    }
}
