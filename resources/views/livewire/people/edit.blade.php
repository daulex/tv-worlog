<div>
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Person</h1>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:input
                        wire:model="first_name"
                        label="First Name"
                        type="text"
                        required
                    />
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="last_name"
                        label="Last Name"
                        type="text"
                        required
                    />
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="pers_code"
                        label="Personal Code"
                        type="text"
                        required
                    />
                    @error('pers_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="email"
                        label="Email"
                        type="email"
                        required
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="email2"
                        label="Secondary Email"
                        type="email"
                    />
                    @error('email2')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="phone"
                        label="Phone"
                        type="tel"
                    />
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="phone2"
                        label="Secondary Phone"
                        type="tel"
                    />
                    @error('phone2')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="date_of_birth"
                        label="Date of Birth"
                        type="date"
                        required
                    />
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:textarea
                        wire:model="address"
                        label="Address"
                    />
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="starting_date"
                        label="Starting Date"
                        type="date"
                    />
                    @error('starting_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="last_working_date"
                        label="Last Working Date"
                        type="date"
                    />
                    @error('last_working_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:input
                        wire:model="position"
                        label="Position"
                        type="text"
                    />
                    @error('position')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:select
                        wire:model="status"
                        label="Status"
                        required
                    >
                        <option value="Candidate">Candidate</option>
                        <option value="Employee">Employee</option>
                        <option value="Retired">Retired</option>
                    </flux:select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:select
                        wire:model="client_id"
                        label="Client"
                    >
                        <option value="">Select Client</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ $client->id == $person->client_id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </flux:select>
                    @error('client_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <flux:select
                        wire:model="vacancy_id"
                        label="Vacancy"
                    >
                        <option value="">Select Vacancy</option>
                        @foreach ($vacancies as $vacancy)
                            <option value="{{ $vacancy->id }}" {{ $vacancy->id == $person->vacancy_id ? 'selected' : '' }}>
                                {{ $vacancy->title }}
                            </option>
                        @endforeach
                    </flux:select>
                    @error('vacancy_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <flux:button variant="primary" type="submit">
                    Update Person
                </flux:button>
                <flux:button variant="ghost" href="{{ route('people.index') }}">
                    Cancel
                </flux:button>
            </div>
        </form>
    </div>
</div>
