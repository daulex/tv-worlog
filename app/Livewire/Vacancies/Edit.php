<?php

namespace App\Livewire\Vacancies;

use App\Models\Client;
use App\Models\Vacancy;
use Livewire\Component;

class Edit extends Component
{
    public Vacancy $vacancy;

    public $title;

    public $description;

    public $date_opened;

    public $date_closed;

    public $budget;

    public $client_id;

    public $status;

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

    public function mount(Vacancy $vacancy)
    {
        $this->vacancy = $vacancy;
        $this->title = $vacancy->title;
        $this->description = $vacancy->description;
        $this->date_opened = $vacancy->date_opened->format('Y-m-d');
        $this->date_closed = $vacancy->date_closed?->format('Y-m-d');
        $this->budget = $vacancy->budget;
        $this->client_id = $vacancy->client_id;
        $this->status = $vacancy->status;
    }

    public function save()
    {
        $this->validate();

        $this->vacancy->update([
            'title' => $this->title,
            'description' => $this->description,
            'date_opened' => $this->date_opened,
            'date_closed' => $this->date_closed,
            'budget' => $this->budget,
            'client_id' => $this->client_id,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Vacancy updated successfully.');

        return redirect()->route('vacancies.index');
    }

    public function render()
    {
        $clients = Client::orderBy('name')->get();

        return view('livewire.vacancies.edit', [
            'clients' => $clients,
        ]);
    }
}
