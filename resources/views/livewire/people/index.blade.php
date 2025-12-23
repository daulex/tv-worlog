<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">People</h1>
        <flux:button href="{{ route('people.create') }}">Add Person</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    <!-- Filters Section -->
    <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input wire:model.live.debounce.300ms="search" id="search" type="text" placeholder="Search people..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-3 py-2" />
            </div>

            <flux:select wire:model.live="statusFilter" label="Status">
                <option value="">All Statuses</option>
                <option value="Candidate">Candidate</option>
                <option value="Employee">Employee</option>
                <option value="Retired">Retired</option>
            </flux:select>

            <flux:select wire:model.live="clientFilter" label="Client">
                <option value="">All Clients</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="vacancyFilter" label="Vacancy">
                <option value="">All Vacancies</option>
                @foreach ($vacancies as $vacancy)
                    <option value="{{ $vacancy->id }}">{{ $vacancy->title }} - {{ $vacancy->client->name }}</option>
                @endforeach
            </flux:select>
        </div>

        @if ($search || $statusFilter || $clientFilter || $vacancyFilter)
            <div class="mt-3 flex items-center justify-between">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Filters applied
                </span>
                <flux:button wire:click="clearFilters" variant="outline" size="sm">
                    Clear Filters
                </flux:button>
            </div>
        @endif
    </div>

    <div class="w-full bg-white rounded-lg shadow overflow-hidden mb-5 border border-gray-200">
        <table class="w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacancy
                    </th>

                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($people as $person)
                    <tr wire:key="{{ $person->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('people.show', $person) }}" class="text-gray-900 dark:text-gray-100 hover:underline">
                                {{ $person->first_name }} {{ $person->last_name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $person->email }}</td>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $person->position ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $person->client?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $person->vacancy?->title ?? '-' }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No people found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $people->links() }}
</div>