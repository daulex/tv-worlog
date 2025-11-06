<div>
    <div class="space-y-6">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">People</h1>
            <flux:button variant="primary" href="{{ route('people.create') }}">
                Add Person
            </flux:button>
        </div>

        <div class="mb-4">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Search people..."
                type="search"
            />
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Position
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($people as $person)
                        <tr wire:key="{{ $person->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $person->first_name }} {{ $person->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $person->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($person->status === 'Employee') 
                                        bg-green-100 text-green-800
                                    @elseif ($person->status === 'Candidate')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $person->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $person->position ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <flux:button variant="ghost" size="sm" href="{{ route('people.edit', $person) }}">
                                    Edit
                                </flux:button>
                                <flux:button variant="danger" size="sm" wire:click="delete({{ $person->id }})" wire:confirm="Are you sure?">
                                    Delete
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No people found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $people->links() }}
    </div>
</div>
