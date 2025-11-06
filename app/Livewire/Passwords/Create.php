<?php

namespace App\Livewire\Passwords;

use App\Models\Password;
use Livewire\Component;

class Create extends Component
{
    public $name;

    public $url;

    public $username;

    public $password;

    public $notes;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'url' => 'nullable|url|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string',
            'notes' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();

        Password::create([
            'name' => $this->name,
            'url' => $this->url,
            'username' => $this->username,
            'password' => $this->password,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Password created successfully.');

        return redirect()->route('passwords.index');
    }

    public function render()
    {
        return view('livewire.passwords.create');
    }
}
