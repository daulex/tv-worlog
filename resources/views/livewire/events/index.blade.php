<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Events</h1>
        <flux:button href="{{ route('events.create') }}">Add Event</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Search events..."
        class="mb-4"
    />

    <div class="bg-white rounded-lg shadow overflow-hidden mb-5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($events as $event)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <flux:badge variant="primary">{{ $event->type }}</flux:badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->start_date->format('M j, Y g:i A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->end_date->format('M j, Y g:i A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->location ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <flux:button variant="ghost" size="sm" href="{{ route('events.edit', $event) }}">Edit</flux:button>
                            <flux:button variant="ghost" size="sm" wire:click="delete({{ $event->id }})" wire:confirm="Are you sure?">Delete</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $events->links() }}
</div>
