<?php

namespace App\Livewire\Files;

use App\Models\File;
use App\Models\Person;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $person_id;

    public $file;

    public $file_category = 'other';

    public $description;

    public $uploaded_at;

    public $people;

    protected $queryString = [
        'person_id' => ['as' => 'person'],
    ];

    public function mount()
    {
        $this->people = Person::orderBy('first_name')->orderBy('last_name')->get();
        $this->uploaded_at = now()->format('Y-m-d\TH:i');
    }

    protected function rules(): array
    {
        return [
            'person_id' => 'required|integer|exists:people,id',
            'file' => 'required|file|max:10240', // Max 10MB
            'file_category' => 'required|in:cv,contract,certificate,other',
            'description' => 'nullable|string|max:255',
            'uploaded_at' => 'required|date',
        ];
    }

    protected $messages = [
        'file.max' => 'The file may not be larger than 10MB.',
        'file.required' => 'Please select a file to upload.',
    ];

    public function save()
    {
        $this->authorize('create', File::class);

        $this->validate();

        // Store the file
        $filePath = $this->file->store('files', 'public');

        // Create file record
        File::create([
            'person_id' => $this->person_id,
            'filename' => $this->file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $this->file->getMimeType(),
            'file_size' => $this->file->getSize(),
            'file_category' => $this->file_category,
            'description' => $this->description,
            'uploaded_at' => $this->uploaded_at,
        ]);

        session()->flash('message', 'File uploaded successfully.');

        return redirect()->route('files.index');
    }

    public function render()
    {
        return view('livewire.files.create', [
            'categories' => [
                'cv' => 'CV/Resume',
                'contract' => 'Contract',
                'certificate' => 'Certificate',
                'other' => 'Other',
            ],
        ]);
    }
}
