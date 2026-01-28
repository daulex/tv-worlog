<form wire:submit="{{ $submitAction ?? 'save' }}" method="post" action="{{ request()->url() }}">
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
                          @if($errors->has('first_name'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('first_name') }}</div>
                          @endif
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Last Name') }}</flux:label>
                         <flux:input
                             type="text"
                             wire:model="last_name"
                             required
                         />
                          @if($errors->has('last_name'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('last_name') }}</div>
                          @endif
                    </flux:field>

                     <flux:field>
                         <flux:label>{{ __('Personal Code') }}</flux:label>
                          <flux:input
                              type="text"
                              wire:model="pers_code"
                              placeholder="XXXXXX-XXXXX"
                              required
                          />
                          @if($errors->has('pers_code'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('pers_code') }}</div>
                          @endif
                     </flux:field>

                     <flux:field>
                         <flux:label>{{ __('Date of Birth') }}</flux:label>
                          <flux:input
                              type="date"
                              wire:model="date_of_birth"
                              required
                          />
                          @if($errors->has('date_of_birth'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('date_of_birth') }}</div>
                          @endif
                     </flux:field>

                     <flux:field>
                         <flux:label>{{ __('Bank Account') }}</flux:label>
                          <flux:input
                              type="text"
                              wire:model="bank_account"
                              placeholder="IBAN or account number"
                          />
                          @if($errors->has('bank_account'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('bank_account') }}</div>
                          @endif
                     </flux:field>

                     <flux:field>
                         <flux:label>{{ __('Address') }}</flux:label>
                          <flux:textarea
                              wire:model="address"
                              rows="3"
                          />
                          @if($errors->has('address'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('address') }}</div>
                          @endif
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
                          @if($errors->has('email'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('email') }}</div>
                          @endif
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Secondary Email') }}</flux:label>
                         <flux:input
                             type="email"
                             wire:model="email2"
                         />
                          @if($errors->has('email2'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('email2') }}</div>
                          @endif
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Phone') }}</flux:label>
                         <flux:input
                             type="tel"
                             wire:model="phone"
                         />
                          @if($errors->has('phone'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('phone') }}</div>
                          @endif
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Secondary Phone') }}</flux:label>
                         <flux:input
                             type="tel"
                             wire:model="phone2"
                         />
                          @if($errors->has('phone2'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('phone2') }}</div>
                          @endif
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
                          @if($errors->has('position'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('position') }}</div>
                          @endif
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Status') }}</flux:label>
                         <flux:select wire:model="status" required>
                             <flux:select.option value="Candidate">Candidate</flux:select.option>
                             <flux:select.option value="Employee">Employee</flux:select.option>
                             <flux:select.option value="Retired">Retired</flux:select.option>
                         </flux:select>
                          @if($errors->has('status'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('status') }}</div>
                          @endif
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Starting Date') }}</flux:label>
                         <flux:input
                             type="date"
                             wire:model="starting_date"
                         />
                          @if($errors->has('starting_date'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('starting_date') }}</div>
                          @endif
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Last Working Date') }}</flux:label>
                         <flux:input
                             type="date"
                             wire:model="last_working_date"
                         />
                          @if($errors->has('last_working_date'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('last_working_date') }}</div>
                          @endif
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
                          @if($errors->has('client_id'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('client_id') }}</div>
                          @endif
                    </flux:field>
                    
                    <flux:field>
                        <flux:label>{{ __('Vacancy') }}</flux:label>
                         <flux:select wire:model="vacancy_id">
                             <flux:select.option value="">Select Vacancy</flux:select.option>
                             @foreach($vacancies as $vacancy)
                                 <flux:select.option value="{{ $vacancy->id }}">{{ $vacancy->title }} - {{ $vacancy->client->name }}</flux:select.option>
                             @endforeach
                         </flux:select>
                          @if($errors->has('vacancy_id'))
                              <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('vacancy_id') }}</div>
                          @endif
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
                      @if($errors->has('linkedin_profile'))
                          <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('linkedin_profile') }}</div>
                      @endif
                </flux:field>
                
                <flux:field>
                    <flux:label>{{ __('GitHub Profile') }}</flux:label>
                     <flux:input
                         type="url"
                         wire:model="github_profile"
                         placeholder="https://github.com/username"
                     />
                      @if($errors->has('github_profile'))
                          <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('github_profile') }}</div>
                      @endif
                </flux:field>
                
                <flux:field>
                    <flux:label>{{ __('Portfolio URL') }}</flux:label>
                     <flux:input
                         type="url"
                         wire:model="portfolio_url"
                         placeholder="https://yourportfolio.com"
                     />
                      @if($errors->has('portfolio_url'))
                          <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('portfolio_url') }}</div>
                      @endif
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
                      @if($errors->has('emergency_contact_name'))
                          <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('emergency_contact_name') }}</div>
                      @endif
                </flux:field>
                
                <flux:field>
                    <flux:label>{{ __('Relationship') }}</flux:label>
                     <flux:input
                         type="text"
                         wire:model="emergency_contact_relationship"
                         placeholder="Spouse, Parent, Sibling, etc."
                     />
                      @if($errors->has('emergency_contact_relationship'))
                          <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('emergency_contact_relationship') }}</div>
                      @endif
                </flux:field>
                
                <flux:field>
                    <flux:label>{{ __('Contact Phone') }}</flux:label>
                     <flux:input
                         type="tel"
                         wire:model="emergency_contact_phone"
                         placeholder="+371 123 45678"
                     />
                      @if($errors->has('emergency_contact_phone'))
                          <div class="mt-3 text-sm font-medium text-red-500 dark:text-red-400" role="alert" aria-live="polite" aria-atomic="true">{{ $errors->first('emergency_contact_phone') }}</div>
                      @endif
                </flux:field>
            </div>
        </flux:fieldset>
    </div>
    
    <div class="mt-6 flex gap-3">
        <flux:button type="submit" variant="primary">{{ $submitText ?? 'Update' }}</flux:button>
        <flux:button href="{{ $cancelUrl ?? route('people.index') }}" variant="outline">{{ $cancelText ?? 'Cancel' }}</flux:button>
        @if(isset($showDeleteButton) && $showDeleteButton)
            <flux:button wire:click="delete" variant="danger" wire:confirm="Are you sure you want to delete this person? This action cannot be undone." class="ml-auto">Delete</flux:button>
        @endif
    </div>
</form>