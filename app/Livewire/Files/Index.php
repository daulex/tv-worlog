<?php

namespace App\Livewire\Files;

use App\Models\File;
use App\Models\Person;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';

    public $fileCategory = '';

    public $personFilter = '';

    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'fileCategory' => ['except' => ''],
        'personFilter' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function mount()
    {
        $this->authorize('viewAny', File::class);
    }

    public function render()
    {
        $query = File::with('person')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('filename', 'like', '%'.$this->search.'%')
                        ->orWhere('description', 'like', '%'.$this->search.'%')
                        ->orWhereHas('person', function ($personQuery) {
                            $personQuery->where('first_name', 'like', '%'.$this->search.'%')
                                ->orWhere('last_name', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->when($this->fileCategory, function ($query) {
                $query->where('file_category', $this->fileCategory);
            })
            ->when($this->personFilter, function ($query) {
                $query->where('person_id', $this->personFilter);
            })
            ->latest('uploaded_at');

        $files = $query->paginate($this->perPage);
        $people = Person::orderBy('name')->get();
        $categories = ['cv', 'contract', 'certificate', 'other'];

        return view('livewire.files.index', [
            'files' => $files,
            'people' => $people,
            'categories' => $categories,
        ]);
    }

    public function deleteFile(File $file)
    {
        $this->authorize('delete', $file);

        $file->delete();
        session()->flash('message', 'File deleted successfully.');
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFileCategory()
    {
        $this->resetPage();
    }

    public function updatedPersonFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'fileCategory', 'personFilter']);
        $this->resetPage();
    }
}
