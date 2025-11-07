<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Vacancies</h1>
        <flux:button href="{{ route('vacancies.create') }}">Add Vacancy</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Search vacancies..."
        class="mb-4"
    />

    <div class="bg-white rounded-lg shadow overflow-hidden mb-5">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($vacancies as $vacancy)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $vacancy->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($vacancy->client)
                                {{ $vacancy->client->name }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($vacancy->status === 'Open') bg-green-100 text-green-800
                                @elseif($vacancy->status === 'Closed') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $vacancy->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($vacancy->budget)
                                ${{ number_format($vacancy->budget, 2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <flux:button variant="ghost" size="sm" href="{{ route('vacancies.edit', $vacancy) }}">Edit</flux:button>
                            <flux:button variant="ghost" size="sm" wire:click="delete({{ $vacancy->id }})" wire:confirm="Are you sure?">Delete</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No vacancies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $vacancies->links() }}
</div>
