<?php

namespace App\Livewire\Passwords;

use App\Models\Password;
use Livewire\Component;

class Edit extends Component
{
    public Password $password;

    public $name;

    public $url;

    public $username;

    public $password_value;

    public $notes;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'url' => 'nullable|url|max:255',
            'username' => 'required|string|max:255',
            'password_value' => 'required|string',
            'notes' => 'nullable|string',
        ];
    }

    public function mount(Password $password)
    {
        $this->password = $password;
        $this->name = $password->name;
        $this->url = $password->url;
        $this->username = $password->username;
        $this->password_value = $password->password;
        $this->notes = $password->notes;
    }

    public function save()
    {
        $this->validate();

        $this->password->update([
            'name' => $this->name,
            'url' => $this->url,
            'username' => $this->username,
            'password' => $this->password_value,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Password updated successfully.');

        return redirect()->route('passwords.index');
    }

    public function render()
    {
        return view('livewire.passwords.edit');
    }
}
