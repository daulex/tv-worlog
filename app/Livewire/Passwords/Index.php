<?php

namespace App\Livewire\Passwords;

use App\Models\Password;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(Password $password)
    {
        $password->delete();
        session()->flash('message', 'Password deleted successfully.');
    }

    public function render()
    {
        $passwords = Password::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('url', 'like', '%'.$this->search.'%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.passwords.index', [
            'passwords' => $passwords,
        ]);
    }
}
