<?php

namespace App\Livewire\Vacancies;

use App\Models\Client;
use App\Models\Vacancy;
use Livewire\Component;

class Create extends Component
{
    public $title;

    public $description;

    public $date_opened;

    public $date_closed;

    public $budget;

    public $client_id;

    public $status = 'Open';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_opened' => 'required|date',
            'date_closed' => 'nullable|date|after_or_equal:date_opened',
            'budget' => 'nullable|numeric|min:0',
            'client_id' => 'required|exists:clients,id',
            'status' => 'required|in:Open,Closed,Paused',
        ];
    }

    public function save()
    {
        $this->validate();

        Vacancy::create([
            'title' => $this->title,
            'description' => $this->description,
            'date_opened' => $this->date_opened,
            'date_closed' => $this->date_closed,
            'budget' => $this->budget,
            'client_id' => $this->client_id,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Vacancy created successfully.');

        return redirect()->route('vacancies.index');
    }

    public function render()
    {
        $clients = Client::orderBy('name')->get();

        return view('livewire.vacancies.create', [
            'clients' => $clients,
        ]);
    }
}
