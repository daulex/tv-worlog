<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;

class Edit extends Component
{
    public Client $client;

    public $name;

    public $address;

    public $contact_email;

    public $contact_phone;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:255',
        ];
    }

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->name = $client->name;
        $this->address = $client->address;
        $this->contact_email = $client->contact_email;
        $this->contact_phone = $client->contact_phone;
    }

    public function save()
    {
        $this->validate();

        $this->client->update([
            'name' => $this->name,
            'address' => $this->address,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
        ]);

        session()->flash('message', 'Client updated successfully.');

        return redirect()->route('clients.index');
    }

    public function render()
    {
        return view('livewire.clients.edit');
    }
}
