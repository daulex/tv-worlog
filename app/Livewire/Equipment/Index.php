<?php

namespace App\Livewire\Equipment;

use App\Models\Equipment;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(Equipment $equipment)
    {
        $equipment->delete();
        session()->flash('message', 'Equipment deleted successfully.');
    }

    public function render()
    {
        $equipment = Equipment::with('currentOwner')
            ->where('brand', 'like', '%'.$this->search.'%')
            ->orWhere('model', 'like', '%'.$this->search.'%')
            ->orWhere('serial', 'like', '%'.$this->search.'%')
            ->orWhereHas('currentOwner', function ($query) {
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
