<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Reimbursement;
use Livewire\Component;

class Reimbursements extends Component
{
    public $reimbursements;

    public $clients;

    public $editingId = null;

    public $showingForm = false;

    // Form fields
    public $client_id = '';

    public $name = '';

    public $amount = '';

    public $notes = '';

    public function mount()
    {
        $this->loadReimbursements();
        $this->clients = Client::orderBy('name')->get();
    }

    public function loadReimbursements()
    {
        $this->reimbursements = Reimbursement::with('client')->latest()->get();
    }

    public function showForm()
    {
        $this->reset(['client_id', 'name', 'amount', 'notes', 'editingId']);
        $this->showingForm = true;
    }

    public function hideForm()
    {
        $this->showingForm = false;
        $this->editingId = null;
        $this->reset(['client_id', 'name', 'amount', 'notes']);
    }

    public function save()
    {
        $this->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        if ($this->editingId) {
            $reimbursement = Reimbursement::find($this->editingId);
            $reimbursement->update([
                'client_id' => $this->client_id,
                'name' => $this->name,
                'amount' => $this->amount,
                'notes' => $this->notes ?: null,
            ]);
        } else {
            Reimbursement::create([
                'client_id' => $this->client_id,
                'name' => $this->name,
                'amount' => $this->amount,
                'notes' => $this->notes ?: null,
            ]);
        }

        $this->loadReimbursements();
        $this->hideForm();
        session()->flash('message', $this->editingId ? 'Reimbursement updated.' : 'Reimbursement added.');
    }

    public function edit($id)
    {
        $reimbursement = Reimbursement::find($id);
        $this->editingId = $id;
        $this->client_id = $reimbursement->client_id;
        $this->name = $reimbursement->name;
        $this->amount = $reimbursement->amount;
        $this->notes = $reimbursement->notes;
        $this->showingForm = true;
    }

    public function delete($id)
    {
        Reimbursement::find($id)->delete();
        $this->loadReimbursements();
        session()->flash('message', 'Reimbursement deleted.');
    }

    public function render()
    {
        return view('livewire.reimbursements');
    }
}
