<?php

namespace App\Livewire\CVs;

use App\Models\CV;
use App\Models\Person;
use Livewire\Component;

class Edit extends Component
{
    public CV $cv;

    public $person_id;

    public $file_path_or_url;

    public $uploaded_at;

    public $people;

    protected function rules(): array
    {
        return [
            'person_id' => 'required|integer|exists:people,id',
            'file_path_or_url' => 'required|string|max:255',
            'uploaded_at' => 'required|date',
        ];
    }

    public function mount(CV $cv)
    {
        $this->cv = $cv;
        $this->person_id = $cv->person_id;
        $this->file_path_or_url = $cv->file_path_or_url;
        $this->uploaded_at = $cv->uploaded_at->format('Y-m-d\TH:i');

        $this->people = Person::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        $this->cv->update([
            'person_id' => $this->person_id,
            'file_path_or_url' => $this->file_path_or_url,
            'uploaded_at' => $this->uploaded_at,
        ]);

        session()->flash('message', 'CV updated successfully.');

        return redirect()->route('c-vs.index');
    }

    public function render()
    {
        return view('livewire.c-vs.edit');
    }
}
