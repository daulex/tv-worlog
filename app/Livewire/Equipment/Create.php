<?php

namespace App\Livewire\Equipment;

use App\Models\Equipment;
use App\Models\Person;
use Livewire\Component;

class Create extends Component
{
    public $brand;

    public $model;

    public $serial;

    public $purchase_date;

    public $purchase_price;

    public $current_holder_id;

    protected function rules(): array
    {
        return [
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial' => 'required|string|unique:equipment,serial',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'current_holder_id' => 'nullable|exists:people,id',
        ];
    }

    public function save()
    {
        $this->validate();

        Equipment::create([
            'brand' => $this->brand,
            'model' => $this->model,
            'serial' => $this->serial,
            'purchase_date' => $this->purchase_date,
            'purchase_price' => $this->purchase_price,
            'current_holder_id' => $this->current_holder_id,
        ]);

        session()->flash('message', 'Equipment created successfully.');

        return redirect()->route('equipment.index');
    }

    public function render()
    {
        $people = Person::orderBy('first_name')->orderBy('last_name')->get();

        return view('livewire.equipment.create', [
            'people' => $people,
        ]);
    }
}
