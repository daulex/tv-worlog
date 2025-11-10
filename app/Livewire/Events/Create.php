<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public $title;

    public $description;

    public $start_date;

    public $end_date;

    public $location;

    public $type;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:Meeting,Interview,Training,Other',
        ];
    }

    public function save()
    {
        $this->authorize('create', Event::class);

        $this->validate();

        Event::create([
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'type' => $this->type,
        ]);

        session()->flash('message', 'Event created successfully.');

        return redirect()->route('events.index');
    }

    public function render()
    {
        return view('livewire.events.create');
    }
}
