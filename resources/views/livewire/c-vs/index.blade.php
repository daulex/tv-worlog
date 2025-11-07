<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">CVs</h1>
        <flux:button href="{{ route('c-vs.create') }}">Add CV</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Search CVs..."
        class="mb-4"
    />

    <div class="w-full bg-white rounded-lg shadow overflow-hidden mb-5 border border-gray-200">
        <table class="w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Person</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Path/URL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($cvs as $cv)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if ($cv->person)
                                {{ $cv->person->name }}
                            @else
                                Deleted Person
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate">{{ $cv->file_path_or_url }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cv->uploaded_at->format('M j, Y g:i A') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <flux:button variant="ghost" size="sm" href="{{ route('c-vs.edit', $cv) }}">Edit</flux:button>
                            <flux:button variant="ghost" size="sm" wire:click="delete({{ $cv->id }})" wire:confirm="Are you sure?">Delete</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No CVs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $cvs->links() }}
</div>
