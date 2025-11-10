<?php

namespace App\Livewire\Equipment;

use App\Models\Equipment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';

    public function delete(Equipment $equipment)
    {
        $this->authorize('delete', $equipment);

        $equipment->delete();
        session()->flash('message', 'Equipment deleted successfully.');
    }

    public function render()
    {
        $this->authorize('viewAny', Equipment::class);

        $equipment = Equipment::with('currentHolder')
            ->where('brand', 'like', '%'.$this->search.'%')
            ->orWhere('model', 'like', '%'.$this->search.'%')
            ->orWhere('serial', 'like', '%'.$this->search.'%')
            ->orWhereHas('currentHolder', function ($query) {
                $query->where('first_name', 'like', '%'.$this->search.'%')
                    ->orWhere('last_name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('livewire.equipment.index', [
            'equipment' => $equipment,
        ]);
    }
}
