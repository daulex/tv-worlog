<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;

class Edit extends Component
{
    public Event $event;

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

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->start_date = $event->start_date->format('Y-m-d\TH:i');
        $this->end_date = $event->end_date->format('Y-m-d\TH:i');
        $this->location = $event->location;
        $this->type = $event->type;
    }

    public function save()
    {
        $this->validate();

        $this->event->update([
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'type' => $this->type,
        ]);

        session()->flash('message', 'Event updated successfully.');

        return redirect()->route('events.index');
    }

    public function render()
    {
        return view('livewire.events.edit');
    }
}
