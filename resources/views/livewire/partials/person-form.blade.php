<form wire:submit="{{ $submitAction ?? 'save' }}">
    <div class="space-y-8">
        <!-- Personal Information Fieldset -->
        <flux:fieldset legend="{{ __('Personal Information') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('First Name') }}</flux:label>
                        <flux:input 
                            type="text" 
                            wire:model="first_name" 
                            required
                        />
                        <flux:error name="first_name" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Last Name') }}</flux:label>
                        <flux:input 
                            type="text" 
                            wire:model="last_name" 
                            required
                        />
                        <flux:error name="last_name" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Personal Code') }}</flux:label>
                        <flux:input 
                            type="text" 
                            wire:model="pers_code"
                            placeholder="XXXXXX-XXXXX"
                            required
                        />
                        <flux:error name="pers_code" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Date of Birth') }}</flux:label>
                        <flux:input 
                            type="date" 
                            wire:model="date_of_birth"
                            required
                        />
                        <flux:error name="date_of_birth" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Address') }}</flux:label>
                        <flux:textarea 
                            wire:model="address" 
                            rows="3"
                        />
                        <flux:error name="address" />
                    </flux:field>
                </div>
                
                <div class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('Email') }}</flux:label>
                        <flux:input 
                            type="email" 
                            wire:model="email"
                            required
                        />
                        <flux:error name="email" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Secondary Email') }}</flux:label>
                        <flux:input 
                            type="email" 
                            wire:model="email2"
                        />
                        <flux:error name="email2" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Phone') }}</flux:label>
                        <flux:input 
                            type="tel" 
                            wire:model="phone"
                        />
                        <flux:error name="phone" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Secondary Phone') }}</flux:label>
                        <flux:input 
                            type="tel" 
                            wire:model="phone2"
                        />
                        <flux:error name="phone2" />
                    </flux:field>
                </div>
            </div>
        </flux:fieldset>

        <!-- Professional Information Fieldset -->
        <flux:fieldset legend="{{ __('Professional Information') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('Position') }}</flux:label>
                        <flux:input 
                            type="text" 
                            wire:model="position"
                        />
                        <flux:error name="position" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Status') }}</flux:label>
                        <flux:select wire:model="status" required>
                            <flux:select.option value="Candidate">Candidate</flux:select.option>
                            <flux:select.option value="Employee">Employee</flux:select.option>
                            <flux:select.option value="Retired">Retired</flux:select.option>
                        </flux:select>
                        <flux:error name="status" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Starting Date') }}</flux:label>
                        <flux:input 
                            type="date" 
                            wire:model="starting_date"
                        />
                        <flux:error name="starting_date" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Last Working Date') }}</flux:label>
                        <flux:input 
                            type="date" 
                            wire:model="last_working_date"
                        />
                        <flux:error name="last_working_date" />
                    </flux:field>
                </div>
                
                <div class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('Client') }}</flux:label>
                        <flux:select wire:model="client_id">
                            <flux:select.option value="">Select Client</flux:select.option>
                            @foreach($clients as $client)
                                <flux:select.option value="{{ $client->id }}">{{ $client->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="client_id" />
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Vacancy') }}</flux:label>
                        <flux:select wire:model="vacancy_id">
                            <flux:select.option value="">Select Vacancy</flux:select.option>
                            @foreach($vacancies as $vacancy)
                                <flux:select.option value="{{ $vacancy->id }}">{{ $vacancy->title }} - {{ $vacancy->client->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="vacancy_id" />
                    </flux:field>
                </div>
            </div>
        </flux:fieldset>

        <!-- Professional Profiles Fieldset -->
        <flux:fieldset legend="{{ __('Professional Profiles') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:field>
                    <flux:label>{{ __('LinkedIn Profile') }}</flux:label>
                    <flux:input 
                        type="url" 
                        wire:model="linkedin_profile"
                        placeholder="https://linkedin.com/in/username"
                    />
                    <flux:error name="linkedin_profile" />
                </flux:field>
                
                <flux:field>
                    <flux:label>{{ __('GitHub Profile') }}</flux:label>
                    <flux:input 
                        type="url" 
                        wire:model="github_profile"
                        placeholder="https://github.com/username"
                    />
                    <flux:error name="github_profile" />
                </flux:field>
                
                <flux:field>
                    <flux:label>{{ __('Portfolio URL') }}</flux:label>
                    <flux:input 
                        type="url" 
                        wire:model="portfolio_url"
                        placeholder="https://yourportfolio.com"
                    />
                    <flux:error name="portfolio_url" />
                </flux:field>
            </div>
        </flux:fieldset>

        <!-- Emergency Contact Fieldset -->
        <flux:fieldset legend="{{ __('Emergency Contact') }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <flux:field>
                    <flux:label>{{ __('Contact Name') }}</flux:label>
                    <flux:input 
                        type="text" 
                        wire:model="emergency_contact_name"
                        placeholder="Emergency contact name"
                    />
                    <flux:error name="emergency_contact_name" />
                </flux:field>
                
                <flux:field>
                    <flux:label>{{ __('Relationship') }}</flux:label>
                    <flux:input 
                        type="text" 
                        wire:model="emergency_contact_relationship"
                        placeholder="Spouse, Parent, Sibling, etc."
                    />
                    <flux:error name="emergency_contact_relationship" />
                </flux:field>
                
                <flux:field>
                    <flux:label>{{ __('Contact Phone') }}</flux:label>
                    <flux:input 
                        type="tel" 
                        wire:model="emergency_contact_phone"
                        placeholder="+371 123 45678"
                    />
                    <flux:error name="emergency_contact_phone" />
                </flux:field>
            </div>
        </flux:fieldset>
    </div>
    
    <div class="flex space-x-4 mt-8">
        <flux:button type="submit" icon="check">
            {{ $submitText ?? __('Save') }}
        </flux:button>
        
        @if(isset($cancelAction))
            <flux:button wire:click="{{ $cancelAction }}" variant="outline" type="button" icon="x-mark">
                {{ $cancelText ?? __('Cancel') }}
            </flux:button>
        @else
            <flux:button href="{{ $cancelUrl ?? route('people.index') }}" variant="outline" icon="x-mark">
                {{ $cancelText ?? __('Cancel') }}
            </flux:button>
        @endif
    </div>
</form>