<?php

namespace App\Livewire\CVs;

use App\Models\CV;
use App\Models\Person;
use Livewire\Component;

class Create extends Component
{
    public $person_id;

    public $file_path_or_url;

    public $uploaded_at;

    public $people;

    public function mount()
    {
        $this->people = Person::orderBy('name')->get();
        $this->uploaded_at = now()->format('Y-m-d\TH:i');
    }

    protected function rules(): array
    {
        return [
            'person_id' => 'required|integer|exists:people,id',
            'file_path_or_url' => 'required|string|max:255',
            'uploaded_at' => 'required|date',
        ];
    }

    public function save()
    {
        $this->validate();

        CV::create([
            'person_id' => $this->person_id,
            'file_path_or_url' => $this->file_path_or_url,
            'uploaded_at' => $this->uploaded_at,
        ]);

        session()->flash('message', 'CV created successfully.');

        return redirect()->route('c-vs.index');
    }

    public function render()
    {
        return view('livewire.c-vs.create');
    }
}
