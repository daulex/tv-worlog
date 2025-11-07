<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Notes</h1>
        <flux:button href="{{ route('notes.create') }}">Add Note</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Search notes..."
        class="mb-4"
    />

    <div class="bg-white rounded-lg shadow overflow-hidden mb-5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Related To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($notes as $note)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate">{{ Str::limit($note->note_text, 100) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <flux:badge variant="primary">{{ ucfirst($note->note_type) }}</flux:badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if ($note->noteable)
                                {{ $note->noteable->name ?? $note->noteable->title ?? $note->noteable->id }}
                            @else
                                Deleted {{ ucfirst($note->note_type) }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $note->created_at->format('M j, Y g:i A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <flux:button variant="ghost" size="sm" href="{{ route('notes.edit', $note) }}">Edit</flux:button>
                            <flux:button variant="ghost" size="sm" wire:click="delete({{ $note->id }})" wire:confirm="Are you sure?">Delete</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No notes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $notes->links() }}
</div>
