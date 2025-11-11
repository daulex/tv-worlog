<?php

namespace App\Livewire\Files;

use App\Models\File;
use App\Models\Person;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;

    public File $file;

    public $person_id;

    public $file_category;

    public $description;

    public $uploaded_at;

    public $people;

    public function mount(File $file)
    {
        $this->authorize('update', $file);
        $this->file = $file;
        $this->person_id = $file->person_id;
        $this->file_category = $file->file_category;
        $this->description = $file->description;
        $this->uploaded_at = $file->uploaded_at->format('Y-m-d\TH:i');

        $this->people = Person::orderBy('name')->get();
    }

    protected function rules(): array
    {
        return [
            'person_id' => 'required|integer|exists:people,id',
            'file_category' => 'required|in:cv,contract,certificate,other',
            'description' => 'nullable|string|max:255',
            'uploaded_at' => 'required|date',
        ];
    }

    public function save()
    {
        $this->authorize('update', $this->file);

        $this->validate();

        $this->file->update([
            'person_id' => $this->person_id,
            'file_category' => $this->file_category,
            'description' => $this->description,
            'uploaded_at' => $this->uploaded_at,
        ]);

        session()->flash('message', 'File updated successfully.');

        return redirect()->route('files.show', $this->file);
    }

    public function render()
    {
        return view('livewire.files.edit', [
            'categories' => [
                'cv' => 'CV/Resume',
                'contract' => 'Contract',
                'certificate' => 'Certificate',
                'other' => 'Other',
            ],
        ]);
    }
}
