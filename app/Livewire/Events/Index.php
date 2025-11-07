<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function delete(Event $event)
    {
        $event->delete();
        session()->flash('message', 'Event deleted successfully.');
    }

    public function render()
    {
        $events = Event::where('title', 'like', '%'.$this->search.'%')
            ->orWhere('description', 'like', '%'.$this->search.'%')
            ->orWhere('location', 'like', '%'.$this->search.'%')
            ->orWhere('type', 'like', '%'.$this->search.'%')
            ->orderBy('start_date', 'desc')
            ->paginate(50);

        return view('livewire.events.index', [
            'events' => $events,
        ]);
    }
}
