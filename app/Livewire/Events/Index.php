<?php

namespace App\Livewire\Events;

use App\Models\Event;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';

    public function delete(Event $event)
    {
        $this->authorize('delete', $event);

        $event->delete();
        session()->flash('message', 'Event deleted successfully.');
    }

    public function render()
    {
        $this->authorize('viewAny', Event::class);

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
