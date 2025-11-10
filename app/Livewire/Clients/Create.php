<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

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

    public function save()
    {
        $this->authorize('create', Client::class);

        $this->validate();

        Client::create([
            'name' => $this->name,
            'address' => $this->address,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
        ]);

        session()->flash('message', 'Client created successfully.');

        return redirect()->route('clients.index');
    }

    public function render()
    {
        return view('livewire.clients.create');
    }
}
