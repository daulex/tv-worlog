<flux:container>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Vacancy</h1>
        <flux:button href="{{ route('vacancies.index') }}">Back</flux:button>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Title</flux:label>
                <flux:input wire:model="title" type="text" required />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label>Client</flux:label>
                <flux:select wire:model="client_id" required>
                    <option value="">Select a client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ $client->id == $client_id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="client_id" />
            </flux:field>

            <flux:field>
                <flux:label>Status</flux:label>
                <flux:select wire:model="status" required>
                    <option value="Open" {{ $status === 'Open' ? 'selected' : '' }}>Open</option>
                    <option value="Closed" {{ $status === 'Closed' ? 'selected' : '' }}>Closed</option>
                    <option value="Paused" {{ $status === 'Paused' ? 'selected' : '' }}>Paused</option>
                </flux:select>
                <flux:error name="status" />
            </flux:field>

            <flux:field>
                <flux:label>Budget</flux:label>
                <flux:input wire:model="budget" type="number" step="0.01" min="0" placeholder="0.00" />
                <flux:error name="budget" />
            </flux:field>

            <flux:field>
                <flux:label>Date Opened</flux:label>
                <flux:input wire:model="date_opened" type="date" required />
                <flux:error name="date_opened" />
            </flux:field>

            <flux:field>
                <flux:label>Date Closed</flux:label>
                <flux:input wire:model="date_closed" type="date" />
                <flux:error name="date_closed" />
            </flux:field>
        </div>

        <flux:field class="mt-6">
            <flux:label>Description</flux:label>
            <flux:textarea wire:model="description" rows="4" />
            <flux:error name="description" />
        </flux:field>

        @if($deleteAttempted && $associatedPeople->count() > 0)
            <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <flux:icon name="exclamation-triangle" class="w-5 h-5 text-amber-400" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-800">
                            Associated People
                        </h3>
                        <div class="mt-2 text-sm text-amber-700">
                            <p>This vacancy has {{ $associatedPeople->count() }} associated {{ Str::plural('person', $associatedPeople->count()) }}. You must unassign them before deleting the vacancy:</p>
                            <ul class="mt-2 space-y-1">
                                @foreach($associatedPeople as $person)
                                    <li class="flex items-center justify-between">
                                        <a href="{{ route('people.show', $person) }}" class="text-amber-800 hover:text-amber-900 underline">
                                            {{ $person->first_name }} {{ $person->last_name }}
                                        </a>
                                        <flux:button wire:click="unassignPerson({{ $person->id }})" variant="outline" size="sm" class="ml-2">
                                            Unassign
                                        </flux:button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($deleteAttempted && $errors->has('delete'))
            <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <flux:icon name="exclamation-triangle" class="w-5 h-5 text-red-400" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Cannot Delete Vacancy
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            {{ $errors->first('delete') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-6 flex gap-3">
            <flux:button type="submit" variant="primary">Update</flux:button>
            <flux:button href="{{ route('vacancies.index') }}" variant="outline">Cancel</flux:button>
            <flux:button wire:click="delete" variant="danger" wire:confirm="Are you sure you want to delete this vacancy? This action cannot be undone." class="ml-auto">Delete</flux:button>
        </div>
    </form>
</flux:container>
