<?php

namespace App\Livewire\People;

use App\Models\Client;
use App\Models\Person;
use App\Models\Vacancy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public $first_name;

    public $last_name;

    public $pers_code;

    public $phone;

    public $phone2;

    public $email;

    public $email2;

    public $date_of_birth;

    public $address;

    public $bank_account;

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
            'phone' => 'nullable|string|max:255',
            'phone2' => 'nullable|string|max:255',
            'email' => 'required|email|unique:people,email',
            'email2' => 'nullable|email|unique:people,email2',
            'date_of_birth' => 'required|date|before:today',
            'address' => 'nullable|string|max:1000',
            'bank_account' => 'nullable|string|max:255',
            'starting_date' => 'nullable|date',
            'last_working_date' => 'nullable|date|before_or_equal:today',
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
        $this->authorize('create', Person::class);

        $this->validate();

        // Custom validation for date range
        if ($this->starting_date && $this->last_working_date) {
            if (strtotime($this->last_working_date) < strtotime($this->starting_date)) {
                $this->addError('last_working_date', 'The last working date must be after or equal to the starting date.');

                return;
            }
        }

        Person::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'pers_code' => $this->pers_code,
            'phone' => $this->phone ?: null,
            'phone2' => $this->phone2 ?: null,
            'email' => $this->email,
            'email2' => $this->email2 ?: null,
            'date_of_birth' => $this->date_of_birth ?: null,
            'address' => $this->address ?: null,
            'bank_account' => $this->bank_account ?: null,
            'starting_date' => $this->starting_date ?: null,
            'last_working_date' => $this->last_working_date ?: null,
            'position' => $this->position ?: null,
            'status' => $this->status,
            'client_id' => $this->client_id ?: null,
            'vacancy_id' => $this->vacancy_id ?: null,
            'linkedin_profile' => $this->linkedin_profile ?: null,
            'github_profile' => $this->github_profile ?: null,
            'portfolio_url' => $this->portfolio_url ?: null,
            'emergency_contact_name' => $this->emergency_contact_name ?: null,
            'emergency_contact_relationship' => $this->emergency_contact_relationship ?: null,
            'emergency_contact_phone' => $this->emergency_contact_phone ?: null,
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
