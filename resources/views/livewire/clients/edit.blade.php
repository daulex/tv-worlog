<flux:container>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Client</h1>
        <flux:button href="{{ route('clients.index') }}">Back</flux:button>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" type="text" required />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="contact_email" type="email" required />
                <flux:error name="contact_email" />
            </flux:field>

            <flux:field>
                <flux:label>Phone</flux:label>
                <flux:input wire:model="contact_phone" type="tel" placeholder="+1 (555) 123-4567" />
                <flux:error name="contact_phone" />
            </flux:field>
        </div>

        <flux:field class="mt-6">
            <flux:label>Address</flux:label>
            <flux:textarea wire:model="address" rows="3" />
            <flux:error name="address" />
        </flux:field>

        @if($deleteAttempted && ($associatedPeople->count() > 0 || $associatedVacancies->count() > 0))
            <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <flux:icon name="exclamation-triangle" class="w-5 h-5 text-amber-400" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-800">
                            Associated Records
                        </h3>
                        <div class="mt-2 text-sm text-amber-700">
                            <p>This client has associated records that must be unassigned before deletion:</p>

                            @if($associatedPeople->count() > 0)
                                <div class="mt-3">
                                    <h4 class="font-medium text-amber-800">People ({{ $associatedPeople->count() }})</h4>
                                    <ul class="mt-1 space-y-1">
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
                            @endif

                            @if($associatedVacancies->count() > 0)
                                <div class="mt-3">
                                    <h4 class="font-medium text-amber-800">Vacancies ({{ $associatedVacancies->count() }})</h4>
                                    <ul class="mt-1 space-y-1">
                                        @foreach($associatedVacancies as $vacancy)
                                            <li class="flex items-center justify-between">
                                                <a href="{{ route('vacancies.show', $vacancy) }}" class="text-amber-800 hover:text-amber-900 underline">
                                                    {{ $vacancy->title }}
                                                </a>
                                                <flux:button wire:click="unassignVacancy({{ $vacancy->id }})" variant="outline" size="sm" class="ml-2">
                                                    Unassign
                                                </flux:button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
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
                            Cannot Delete Client
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
            <flux:button href="{{ route('clients.index') }}" variant="outline">Cancel</flux:button>
            <flux:button wire:click="delete" variant="danger" wire:confirm="Are you sure you want to delete this client? This action cannot be undone." class="ml-auto">Delete</flux:button>
        </div>
    </form>
</flux:container>
