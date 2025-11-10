<?php

namespace App\Livewire\People;

use App\Models\Client;
use App\Models\Person;
use App\Models\Vacancy;
use Livewire\Component;

class Create extends Component
{
    public $first_name;

    public $last_name;

    public $pers_code;

    public $phone;

    public $email;

    public $date_of_birth;

    public $address;

    public $starting_date;

    public $last_working_date;

    public $position;

    public $status;

    public $client_id;

    public $vacancy_id;

    public $linkedin_profile;

    public $github_profile;

    public $portfolio_url;

    public $emergency_contact_name;

    public $emergency_contact_relationship;

    public $emergency_contact_phone;

    protected function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'pers_code' => 'required|string|unique:people,pers_code',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:people,email',
            'date_of_birth' => 'required|date',
            'address' => 'nullable|string',
            'starting_date' => 'nullable|date',
            'last_working_date' => 'nullable|date',
            'position' => 'nullable|string|max:255',
            'status' => 'required|in:Candidate,Employee,Retired',
            'client_id' => 'nullable|exists:clients,id',
            'vacancy_id' => 'nullable|exists:vacancies,id',
            'linkedin_profile' => 'nullable|url|max:500',
            'github_profile' => 'nullable|url|max:500',
            'portfolio_url' => 'nullable|url|max:500',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        Person::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'pers_code' => $this->pers_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
            'address' => $this->address,
            'starting_date' => $this->starting_date,
            'last_working_date' => $this->last_working_date,
            'position' => $this->position,
            'status' => $this->status,
            'client_id' => $this->client_id,
            'vacancy_id' => $this->vacancy_id,
            'linkedin_profile' => $this->linkedin_profile,
            'github_profile' => $this->github_profile,
            'portfolio_url' => $this->portfolio_url,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_relationship' => $this->emergency_contact_relationship,
            'emergency_contact_phone' => $this->emergency_contact_phone,
        ]);

        session()->flash('message', 'Person created successfully.');

        return redirect()->route('people.index');
    }

    public function render()
    {
        return view('livewire.people.create', [
            'clients' => Client::all(),
            'vacancies' => Vacancy::with('client')->get(),
        ]);
    }
}
