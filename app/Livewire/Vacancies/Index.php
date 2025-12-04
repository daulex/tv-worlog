<?php

namespace App\Livewire\Vacancies;

use App\Models\Vacancy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';

    public function render()
    {
        $this->authorize('viewAny', Vacancy::class);

        $vacancies = Vacancy::with('client')
            ->where('title', 'like', '%'.$this->search.'%')
            ->orWhere('description', 'like', '%'.$this->search.'%')
            ->orWhere('status', 'like', '%'.$this->search.'%')
            ->orWhereHas('client', function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('livewire.vacancies.index', [
            'vacancies' => $vacancies,
        ]);
    }
}
