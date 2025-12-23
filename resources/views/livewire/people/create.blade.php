<flux:container>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Create Person</h1>
        <flux:button href="{{ route('people.index') }}">Back</flux:button>
    </div>

    <form wire:submit="save" method="post">
        @csrf
                <!-- Personal Information Fieldset -->
                <flux:fieldset legend="{{ __('Personal Information') }}">
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
                                wire:model="date_of_birth"
                                label="Date of Birth"
                                type="date"
                                required
                            />
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <flux:textarea
                                wire:model="address"
                                label="Address"
                            />
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </flux:fieldset>

                <!-- Contact Information Fieldset -->
                <flux:fieldset legend="{{ __('Contact Information') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                wire:model="phone"
                                label="Phone"
                                type="tel"
                            />
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </flux:fieldset>

                <!-- Professional Information Fieldset -->
                <flux:fieldset legend="{{ __('Professional Information') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                                <option value="">Select Status</option>
                                <option value="Candidate">Candidate</option>
                                <option value="Employee">Employee</option>
                                <option value="Retired">Retired</option>
                            </flux:select>
                            @error('status')
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
                            <flux:select
                                wire:model="client_id"
                                label="Client"
                            >
                                <option value="">Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
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
                                    <option value="{{ $vacancy->id }}">{{ $vacancy->title }} - {{ $vacancy->client->name }}</option>
                                @endforeach
                            </flux:select>
                            @error('vacancy_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </flux:fieldset>

                <!-- Professional Profiles Fieldset -->
                <flux:fieldset legend="{{ __('Professional Profiles') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <flux:input
                                wire:model="linkedin_profile"
                                label="LinkedIn Profile"
                                type="url"
                                placeholder="https://linkedin.com/in/username"
                            />
                            @error('linkedin_profile')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <flux:input
                                wire:model="github_profile"
                                label="GitHub Profile"
                                type="url"
                                placeholder="https://github.com/username"
                            />
                            @error('github_profile')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <flux:input
                                wire:model="portfolio_url"
                                label="Portfolio URL"
                                type="url"
                                placeholder="https://yourportfolio.com"
                            />
                            @error('portfolio_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </flux:fieldset>

                <!-- Emergency Contact Fieldset -->
                <flux:fieldset legend="{{ __('Emergency Contact') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <flux:input
                                wire:model="emergency_contact_name"
                                label="Contact Name"
                                type="text"
                                placeholder="Emergency contact name"
                            />
                            @error('emergency_contact_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <flux:input
                                wire:model="emergency_contact_relationship"
                                label="Relationship"
                                type="text"
                                placeholder="Spouse, Parent, Sibling, etc."
                            />
                            @error('emergency_contact_relationship')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <flux:input
                                wire:model="emergency_contact_phone"
                                label="Contact Phone"
                                type="tel"
                                placeholder="+371 123 45678"
                            />
                            @error('emergency_contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                 </flux:fieldset>

                 <div class="mt-6 flex gap-3">
                     <flux:button type="submit" variant="primary">Save</flux:button>
                     <flux:button href="{{ route('people.index') }}" variant="outline">Cancel</flux:button>
                 </div>
             </form>
</flux:container>
