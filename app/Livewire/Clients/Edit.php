<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;

    public Client $client;

    public $deleteAttempted = false;

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

    public function delete()
    {
        $this->authorize('delete', $this->client);

        $this->deleteAttempted = true;

        // Check for related records that would prevent deletion
        $relatedPeople = $this->client->people();
        $relatedVacancies = $this->client->vacancies();

        if ($relatedPeople->exists() || $relatedVacancies->exists()) {
            $this->addError('delete', 'Cannot delete this client because it has associated records. Please reassign or remove these associations first.');

            return;
        }

        $this->client->delete();

        return redirect()->route('clients.index')->with('message', 'Client deleted successfully.');
    }

    public function unassignPerson($personId)
    {
        $this->authorize('update', $this->client);

        $person = $this->client->people()->findOrFail($personId);

        $person->update(['client_id' => null]);

        // Clear any delete errors and reset the attempted flag
        $this->resetErrorBag('delete');
        $this->deleteAttempted = false;

        session()->flash('message', 'Person unassigned from client successfully.');
    }

    public function unassignVacancy($vacancyId)
    {
        $this->authorize('update', $this->client);

        $vacancy = $this->client->vacancies()->findOrFail($vacancyId);

        $vacancy->update(['client_id' => null]);

        // Clear any delete errors and reset the attempted flag
        $this->resetErrorBag('delete');
        $this->deleteAttempted = false;

        session()->flash('message', 'Vacancy unassigned from client successfully.');
    }

    public function render()
    {
        return view('livewire.clients.edit', [
            'associatedPeople' => $this->client->people()->select('id', 'first_name', 'last_name')->get(),
            'associatedVacancies' => $this->client->vacancies()->select('id', 'title')->get(),
        ]);
    }
}
