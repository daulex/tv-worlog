<?php

namespace App\Livewire\Files;

use App\Models\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public File $file;

    public function mount(File $file)
    {
        $this->authorize('view', $file);
        $this->file = $file;
    }

    public function download()
    {
        $this->authorize('download', $this->file);

        if (! Storage::disk('public')->exists($this->file->file_path)) {
            session()->flash('error', 'File not found on server.');

            return;
        }

        return Storage::disk('public')->download($this->file->file_path, $this->file->filename);
    }

    public function delete()
    {
        $this->authorize('delete', $this->file);

        // Delete file from storage
        if (Storage::disk('public')->exists($this->file->file_path)) {
            Storage::disk('public')->delete($this->file->file_path);
        }

        // Delete database record
        $this->file->delete();

        session()->flash('message', 'File deleted successfully.');

        return redirect()->route('files.index');
    }

    public function render()
    {
        return view('livewire.files.show');
    }
}
