<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Passwords</h1>
        <flux:button href="{{ route('passwords.create') }}">Add Password</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Search passwords..."
        class="mb-4"
    />

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($passwords as $password)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $password->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($password->url)
                                <a href="{{ $password->url }}" target="_blank" class="text-blue-600 hover:text-blue-900">{{ $password->url }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $password->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <flux:button variant="ghost" size="sm" href="{{ route('passwords.edit', $password) }}">Edit</flux:button>
                            <flux:button variant="ghost" size="sm" wire:click="delete({{ $password->id }})" wire:confirm="Are you sure?">Delete</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No passwords found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $passwords->links() }}
</div>
