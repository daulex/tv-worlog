<?php

namespace App\Livewire\People;

use App\Models\Client;
use App\Models\Person;
use App\Models\Vacancy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;

    public Person $person;

    public $first_name;

    public $last_name;

    public $pers_code;

    public $phone;

    public $phone2;

    public $email;

    public $email2;

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
            'pers_code' => 'required|string|unique:people,pers_code,'.$this->person->id,
            'phone' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'email' => 'required|email:rfc,spoof|unique:people,email,'.$this->person->id,
            'email2' => 'nullable|email:rfc,spoof|unique:people,email2,'.$this->person->id,
            'date_of_birth' => 'required|date|before:today',
            'address' => 'nullable|string|max:1000',
            'starting_date' => 'nullable|date|before_or_equal:today',
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
            'emergency_contact_phone' => 'nullable|string|max:20',
        ];
    }

    public function mount(Person $person)
    {
        $this->authorize('update', $person);

        $this->person = $person;
        $this->first_name = $person->first_name;
        $this->last_name = $person->last_name;
        $this->pers_code = $person->pers_code;
        $this->phone = $person->phone;
        $this->phone2 = $person->phone2;
        $this->email = $person->email;
        $this->email2 = $person->email2;
        $this->date_of_birth = $person->date_of_birth?->format('Y-m-d');
        $this->address = $person->address;
        $this->starting_date = $person->starting_date?->format('Y-m-d');
        $this->last_working_date = $person->last_working_date?->format('Y-m-d');
        $this->position = $person->position;
        $this->status = $person->status;
        $this->client_id = $person->client_id;
        $this->vacancy_id = $person->vacancy_id;
        $this->linkedin_profile = $person->linkedin_profile;
        $this->github_profile = $person->github_profile;
        $this->portfolio_url = $person->portfolio_url;
        $this->emergency_contact_name = $person->emergency_contact_name;
        $this->emergency_contact_relationship = $person->emergency_contact_relationship;
        $this->emergency_contact_phone = $person->emergency_contact_phone;
    }

    public function save()
    {
        $this->authorize('update', $this->person);

        $this->validate();

        // Custom validation for date range
        if ($this->starting_date && $this->last_working_date) {
            if (strtotime($this->last_working_date) < strtotime($this->starting_date)) {
                $this->addError('last_working_date', 'The last working date must be after or equal to the starting date.');

                return;
            }
        }

        $this->person->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'pers_code' => $this->pers_code,
            'phone' => $this->phone ?: null,
            'phone2' => $this->phone2 ?: null,
            'email' => $this->email,
            'email2' => $this->email2 ?: null,
            'date_of_birth' => $this->date_of_birth ?: null,
            'address' => $this->address ?: null,
            'starting_date' => $this->starting_date ?: null,
            'last_working_date' => $this->last_working_date ?: null,
            'position' => $this->position,
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

        session()->flash('message', 'Person updated successfully.');

        return redirect()->route('people.show', $this->person);
    }

    public function render()
    {
        return view('livewire.people.edit', [
            'clients' => Client::all(),
            'vacancies' => Vacancy::with('client')->get(),
        ]);
    }
}
