<?php

namespace App\Livewire\Equipment;

use App\Models\Equipment;
use App\Models\Person;
use Livewire\Component;

class Edit extends Component
{
    public Equipment $equipment;

    public $brand;

    public $model;

    public $serial;

    public $purchase_date;

    public $purchase_price;

    public $current_owner_id;

    protected function rules(): array
    {
        return [
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial' => 'required|string|unique:equipment,serial,'.$this->equipment->id,
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
            'current_owner_id' => 'nullable|exists:people,id',
        ];
    }

    public function mount(Equipment $equipment)
    {
        $this->equipment = $equipment;
        $this->brand = $equipment->brand;
        $this->model = $equipment->model;
        $this->serial = $equipment->serial;
        $this->purchase_date = $equipment->purchase_date->format('Y-m-d');
        $this->purchase_price = $equipment->purchase_price;
        $this->current_owner_id = $equipment->current_owner_id;
    }

    public function save()
    {
        $this->validate();

        $this->equipment->update([
            'brand' => $this->brand,
            'model' => $this->model,
            'serial' => $this->serial,
            'purchase_date' => $this->purchase_date,
            'purchase_price' => $this->purchase_price,
            'current_owner_id' => $this->current_owner_id,
        ]);

        session()->flash('message', 'Equipment updated successfully.');

        return redirect()->route('equipment.index');
    }

    public function render()
    {
        $people = Person::orderBy('first_name')->orderBy('last_name')->get();

        return view('livewire.equipment.edit', [
            'people' => $people,
        ]);
    }
}
