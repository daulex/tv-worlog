<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';

    public function render()
    {
        $this->authorize('viewAny', Client::class);

        $clients = Client::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('contact_email', 'like', '%'.$this->search.'%')
            ->orWhere('contact_phone', 'like', '%'.$this->search.'%')
            ->orderBy('name')
            ->paginate(50);

        return view('livewire.clients.index', [
            'clients' => $clients,
        ]);
    }
}
