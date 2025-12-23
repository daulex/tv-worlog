<flux:container>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Create Person</h1>
        <flux:button href="{{ route('people.index') }}">Back</flux:button>
    </div>

    <form action="{{ route('people.store') }}" method="post">
        @csrf
                <!-- Personal Information Fieldset -->
                <flux:fieldset legend="{{ __('Personal Information') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                             <flux:input
                                 name="first_name"
                                 value="{{ old('first_name') }}"
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
                                 name="last_name"
                                 value="{{ old('last_name') }}"
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
                                 name="pers_code"
                                 value="{{ old('pers_code') }}"
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
                                 name="date_of_birth"
                                 value="{{ old('date_of_birth') }}"
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
                                 name="address"
                                 label="Address"
                             >{{ old('address') }}</flux:textarea>
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
                                 name="email"
                                 value="{{ old('email') }}"
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
                                 name="phone"
                                 value="{{ old('phone') }}"
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
                                 name="position"
                                 value="{{ old('position') }}"
                                 label="Position"
                                 type="text"
                             />
                            @error('position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                             <flux:select
                                 name="status"
                                 label="Status"
                                 required
                             >
                                 <option value="">Select Status</option>
                                 <option value="Candidate" {{ old('status') == 'Candidate' ? 'selected' : '' }}>Candidate</option>
                                 <option value="Employee" {{ old('status') == 'Employee' ? 'selected' : '' }}>Employee</option>
                                 <option value="Retired" {{ old('status') == 'Retired' ? 'selected' : '' }}>Retired</option>
                             </flux:select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                             <flux:input
                                 name="starting_date"
                                 value="{{ old('starting_date') }}"
                                 label="Starting Date"
                                 type="date"
                             />
                            @error('starting_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                             <flux:input
                                 name="last_working_date"
                                 value="{{ old('last_working_date') }}"
                                 label="Last Working Date"
                                 type="date"
                             />
                            @error('last_working_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                             <flux:select
                                 name="client_id"
                                 label="Client"
                             >
                                 <option value="">Select Client</option>
                                 @foreach ($clients as $client)
                                     <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                 @endforeach
                             </flux:select>
                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                             <flux:select
                                 name="vacancy_id"
                                 label="Vacancy"
                             >
                                 <option value="">Select Vacancy</option>
                                 @foreach ($vacancies as $vacancy)
                                     <option value="{{ $vacancy->id }}" {{ old('vacancy_id') == $vacancy->id ? 'selected' : '' }}>{{ $vacancy->title }} - {{ $vacancy->client->name }}</option>
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
                                 name="linkedin_profile"
                                 value="{{ old('linkedin_profile') }}"
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
                                 name="github_profile"
                                 value="{{ old('github_profile') }}"
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
                                 name="portfolio_url"
                                 value="{{ old('portfolio_url') }}"
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
                                 name="emergency_contact_name"
                                 value="{{ old('emergency_contact_name') }}"
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
                                 name="emergency_contact_relationship"
                                 value="{{ old('emergency_contact_relationship') }}"
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
                                 name="emergency_contact_phone"
                                 value="{{ old('emergency_contact_phone') }}"
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
