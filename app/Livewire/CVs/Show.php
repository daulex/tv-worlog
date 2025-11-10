<?php

namespace App\Livewire\CVs;

use App\Models\CV;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public CV $cv;

    public function mount(CV $cv)
    {
        $this->authorize('view', $cv);

        $this->cv = $cv->load([
            'person',
        ]);
    }

    public function download()
    {
        $this->authorize('view', $this->cv);

        if ($this->cv->file_path && storage::disk('public')->exists($this->cv->file_path)) {
            return response()->download(storage_path('app/public/'.$this->cv->file_path));
        }

        session()->flash('error', 'CV file not found.');

        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.c-vs.show', [
            'cv' => $this->cv,
        ]);
    }
}
