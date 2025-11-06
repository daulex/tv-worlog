<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(Client $client)
    {
        $client->delete();
        session()->flash('message', 'Client deleted successfully.');
    }

    public function render()
    {
        $clients = Client::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('contact_email', 'like', '%'.$this->search.'%')
            ->orWhere('contact_phone', 'like', '%'.$this->search.'%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.clients.index', [
            'clients' => $clients,
        ]);
    }
}
