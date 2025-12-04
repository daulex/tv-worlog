<div>
    <!-- Person Details Header -->
    <flux:container>
        <flux:heading size="xl">{{ $person->full_name }}</flux:heading>
    </flux:container>

    <!-- Person Information -->
    <flux:container>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <flux:badge variant="{{ $person->status === 'Employee' ? 'success' : ($person->status === 'Candidate' ? 'warning' : 'danger') }}">
                    {{ __($person->status) }}
                </flux:badge>
            </div>
             <div class="flex space-x-2">
                 <flux:button href="{{ route('people.index') }}" variant="outline" size="sm" icon="arrow-left">
                     {{ __('Back to People') }}
                 </flux:button>
                 <flux:button href="{{ route('people.edit', $person) }}" variant="outline" size="sm" icon="pencil">
                     {{ __('Edit') }}
                 </flux:button>
             </div>
        </div>
        
        <!-- View Mode -->
            <div class="space-y-8">
                <!-- Personal Information Fieldset -->
                <flux:fieldset legend="{{ __('Personal Information') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            @include('livewire.partials.field-view', [
                                'label' => __('First Name'),
                                'value' => $person->first_name,
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Last Name'),
                                'value' => $person->last_name,
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Personal Code'),
                                'value' => $person->pers_code,
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Date of Birth'),
                                'value' => $person->date_of_birth?->format('M d, Y'),
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Address'),
                                'value' => $person->address,
                            ])
                        </div>
                        
                        <div class="space-y-4">
                            @include('livewire.partials.field-view', [
                                'label' => __('Email'),
                                'value' => $person->email,
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Secondary Email'),
                                'value' => $person->email2,
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Phone'),
                                'value' => $person->phone,
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Secondary Phone'),
                                'value' => $person->phone2,
                            ])
                        </div>
                    </div>
                </flux:fieldset>

                <!-- Professional Information Fieldset -->
                <flux:fieldset legend="{{ __('Professional Information') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            @include('livewire.partials.field-view', [
                                'label' => __('Position'),
                                'value' => $person->position,
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Status'),
                                'value' => __($person->status),
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Starting Date'),
                                'value' => $person->starting_date?->format('M d, Y'),
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Last Working Date'),
                                'value' => $person->last_working_date?->format('M d, Y'),
                            ])
                        </div>
                        
                        <div class="space-y-4">
                            @include('livewire.partials.field-view', [
                                'label' => __('Client'),
                                'value' => $person->client?->name,
                                'isUnassigned' => !$person->client,
                            ])
                            
                            @include('livewire.partials.field-view', [
                                'label' => __('Vacancy'),
                                'value' => $person->vacancy?->title,
                                'isUnassigned' => !$person->vacancy,
                            ])
                        </div>
                    </div>
                </flux:fieldset>

                <!-- Files Section -->
                @if ($person->files->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Files ({{ $person->files->count() }})</h2>
                        <div class="space-y-3">
                            @foreach ($person->files as $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $file->filename }}</div>
                                        @if ($file->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $file->description }}</div>
                                        @endif
                                        <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                            {{ $file->file_size_formatted }} • {{ ucfirst($file->file_category) }} • {{ $file->uploaded_at->format('M j, Y') }}
                                        </div>
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <flux:button variant="ghost" size="sm" href="{{ route('files.show', $file) }}">
                                            View
                                        </flux:button>
                                        <flux:button variant="outline" size="sm" href="{{ route('files.download', $file) }}">
                                            Download
                                        </flux:button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($person->files->count() > 3)
                            <div class="mt-4 text-center">
                                <flux:button href="{{ route('files.index', ['personFilter' => $person->id]) }}" variant="ghost">
                                    View All Files →
                                </flux:button>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Files</h2>
                        <div class="text-center py-8">
                            <flux:icon name="document" class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                            <p class="text-gray-500 dark:text-gray-400">No files uploaded for this person</p>
                            <flux:button href="{{ route('files.create', ['person' => $person->id]) }}" variant="primary" class="mt-4">
                                Add First File
                            </flux:button>
                        </div>
                    </div>
                @endif

                <!-- Professional Profiles Fieldset -->
                <flux:fieldset legend="{{ __('Professional Profiles') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @include('livewire.partials.field-view', [
                            'label' => __('LinkedIn Profile'),
                            'value' => $person->linkedin_profile,
                        ])
                        
                        @include('livewire.partials.field-view', [
                            'label' => __('GitHub Profile'),
                            'value' => $person->github_profile,
                        ])
                        
                        @include('livewire.partials.field-view', [
                            'label' => __('Portfolio URL'),
                            'value' => $person->portfolio_url,
                        ])
                    </div>
                </flux:fieldset>

                <!-- Emergency Contact Fieldset -->
                <flux:fieldset legend="{{ __('Emergency Contact') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @include('livewire.partials.field-view', [
                            'label' => __('Contact Name'),
                            'value' => $person->emergency_contact_name,
                        ])
                        
                        @include('livewire.partials.field-view', [
                            'label' => __('Relationship'),
                            'value' => $person->emergency_contact_relationship,
                        ])
                        
                        @include('livewire.partials.field-view', [
                            'label' => __('Contact Phone'),
                            'value' => $person->emergency_contact_phone,
                        ])
                    </div>
                </flux:fieldset>
            </div>

    </flux:container>

    <!-- Add Note Section -->
    <flux:container class="py-5 mb-5">
        <flux:heading level="2" size="xl" class="mt-4 mb-4">{{ __('Add Note') }}</flux:heading>
        
        @if($showNoteForm)
            <div class="space-y-4">
                <flux:field>
                    <flux:textarea 
                        wire:model="newNote" 
                        rows="3"
                        placeholder="{{ __('Enter your note here...') }}"
                        required
                    />
                    @error('newNote')
                        <flux:text color="red">{{ $message }}</flux:text>
                    @enderror
                </flux:field>
                
                <div class="flex space-x-4">
                    <flux:button wire:click="addNote" icon="check">
                        {{ __('Add Note') }}
                    </flux:button>
                    
                    <flux:button wire:click="toggleNoteForm" variant="outline" icon="x-mark">
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </div>
        @else
            <flux:button wire:click="toggleNoteForm" variant="outline" icon="plus">
                {{ __('Add Note') }}
            </flux:button>
        @endif
    </flux:container>

    <!-- Unified Timeline -->
    <flux:container>
        <flux:heading level="2" size="xl" class="mb-6">{{ __('Timeline & History') }}</flux:heading>
        
        <!-- Unified Timeline -->
        @php
            $timeline = $this->getTimeline();
        @endphp
        
        @if($timeline->count() > 0)
            <div class="relative pl-8">
                <!-- Timeline Line -->
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-400 dark:bg-gray-500"></div>
                
                <!-- Timeline Items -->
                <div class="space-y-4">
                    @foreach($timeline as $item)
                        @if($item['type'] === 'note')
                            @php
                                $note = $item['data'];
                            @endphp
                            <div class="relative flex items-start">
                                <!-- Timeline Icon -->
                                <div class="absolute -left-8 flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center ring-4 ring-white dark:ring-gray-800 border-2 border-blue-200 dark:border-blue-800">
                                    <flux:icon name="document-text" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                </div>
                                
                                <!-- Timeline Content -->
                                <div class="flex-1 min-w-0 ml-8">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center space-x-2">
                                                <flux:badge variant="outline" class="bg-blue-50 text-blue-700 border-blue-200 text-xs">
                                                    {{ __('Note') }}
                                                </flux:badge>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $note->created_at->setTimezone(config('app.timezone'))->format('M d, Y H:i') }}
                                                </span>
                                            </div>
                                            
                                            <!-- Note Management Buttons -->
                                            @if($editingNote == $note->id)
                                                <flux:button wire:click="cancelNoteEdit" variant="outline" size="sm">
                                                    <flux:icon name="x-mark" class="w-3 h-3" />
                                                </flux:button>
                                            @else
                                                <div class="flex items-center space-x-1">
                                                    <flux:button wire:click="editNote({{ $note->id }})" variant="outline" size="sm" icon="pencil">
                                                    </flux:button>
                                                    <flux:button wire:click="deleteNote({{ $note->id }})" variant="outline" size="sm" class="text-red-600 hover:text-red-700" icon="trash">
                                                    </flux:button>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Note Content -->
                                        @if($editingNote == $note->id)
                                            <div class="mt-3">
                                                <flux:field>
                                                    <flux:textarea 
                                                        wire:model="noteEditForm.note_text" 
                                                        rows="3"
                                                        required
                                                    />
                                                    @error('noteEditForm.note_text')
                                                        <flux:text color="red">{{ $message }}</flux:text>
                                                    @enderror
                                                </flux:field>
                                                
                                                <div class="flex space-x-4 mt-3">
                                                        <flux:button wire:click="saveNoteEdit" type="button" size="sm" icon="check">
                                                            {{ __('Save') }}
                                                        </flux:button>
                                                        
                                                        <flux:button wire:click="cancelNoteEdit" variant="outline" type="button" size="sm" icon="x-mark">
                                                            {{ __('Cancel') }}
                                                        </flux:button>
                                                    </div>
                                            </div>
                                        @else
                                            <flux:text class="text-gray-700 dark:text-gray-300">{{ $note->note_text }}</flux:text>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif($item['type'] === 'history')
                            @php
                                $history = $item['data'];
                            @endphp
                            <div class="relative flex items-start">
                                <!-- Timeline Icon -->
                                <div class="absolute -left-8 flex-shrink-0 w-8 h-8 rounded-full bg-{{ $history->action_type_color }}-100 dark:bg-{{ $history->action_type_color }}-900 flex items-center justify-center ring-4 ring-white dark:ring-gray-800 border-2 border-{{ $history->action_type_color }}-200 dark:border-{{ $history->action_type_color }}-800">
                                    <flux:icon :name="$history->action_type_icon" class="w-4 h-4 text-{{ $history->action_type_color }}-600 dark:text-{{ $history->action_type_color }}-400" />
                                </div>
                                
                                <!-- Timeline Content -->
                                <div class="flex-1 min-w-0 ml-8">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                @switch($history->action_type)
                                                    @case('profile_updated')
                                                        <flux:badge variant="outline" class="bg-blue-50 text-blue-700 border-blue-200 text-xs">
                                                            {{ __('Profile Updated') }}
                                                        </flux:badge>
                                                        @break
                                                    @case('equipment_assigned')
                                                        <flux:badge variant="outline" class="bg-orange-50 text-orange-700 border-orange-200 text-xs">
                                                            {{ __('Equipment Assigned') }}
                                                        </flux:badge>
                                                        @break
                                                    @case('equipment_returned')
                                                        <flux:badge variant="outline" class="bg-yellow-50 text-yellow-700 border-yellow-200 text-xs">
                                                            {{ __('Equipment Returned') }}
                                                        </flux:badge>
                                                        @break
                                                    @case('event_joined')
                                                        <flux:badge variant="outline" class="bg-green-50 text-green-700 border-green-200 text-xs">
                                                            {{ __('Joined Event') }}
                                                        </flux:badge>
                                                        @break
                                                    @case('event_left')
                                                        <flux:badge variant="outline" class="bg-red-50 text-red-700 border-red-200 text-xs">
                                                            {{ __('Left Event') }}
                                                        </flux:badge>
                                                        @break
                                                    @case('note_added')
                                                        <flux:badge variant="outline" class="bg-blue-50 text-blue-700 border-blue-200 text-xs">
                                                            {{ __('Note Added') }}
                                                        </flux:badge>
                                                        @break
                                                    @case('file_updated')
                                                        <flux:badge variant="outline" class="bg-purple-50 text-purple-700 border-purple-200 text-xs">
                                                            {{ __('File Updated') }}
                                                        </flux:badge>
                                                        @break
                                                    @case('vacancy_assigned')
                                                        <flux:badge variant="outline" class="bg-indigo-50 text-indigo-700 border-indigo-200 text-xs">
                                                            {{ __('Vacancy Assigned') }}
                                                        </flux:badge>
                                                        @break
                                                    @case('vacancy_removed')
                                                        <flux:badge variant="outline" class="bg-red-50 text-red-700 border-red-200 text-xs">
                                                            {{ __('Vacancy Removed') }}
                                                        </flux:badge>
                                                        @break
                                                    @default
                                                        <flux:badge variant="outline" class="bg-gray-50 text-gray-700 border-gray-200 text-xs">
                                                            {{ __(ucfirst($history->action_type)) }}
                                                        </flux:badge>
                                                @endswitch
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $history->change_date->setTimezone(config('app.timezone'))->format('M d, Y H:i') }}
                                                </span>
                                                @if($history->performedBy)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ __('by :name', ['name' => $history->performedBy->full_name]) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- History Content -->
                                        @if($history->notes)
                                            <flux:text class="text-gray-700 dark:text-gray-300">{{ $history->notes }}</flux:text>
                                        @else
                                            <flux:text class="text-gray-700 dark:text-gray-300">{{ $history->action }}</flux:text>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif($item['type'] === 'event')
                            @php
                                $event = $item['data'];
                            @endphp
                            <div class="relative flex items-start">
                                <!-- Timeline Icon -->
                                <div class="absolute -left-8 flex-shrink-0 w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center ring-4 ring-white dark:ring-gray-800 border-2 border-green-200 dark:border-green-800">
                                    <flux:icon name="calendar" class="w-4 h-4 text-green-600 dark:text-green-400" />
                                </div>
                                
                                <!-- Timeline Content -->
                                <div class="flex-1 min-w-0 ml-8">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center space-x-2">
                                                <flux:badge variant="outline" class="bg-green-50 text-green-700 border-green-200 text-xs">
                                                    {{ __('Event') }}
                                                </flux:badge>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $event->start_date->format('M d, Y H:i') }}
                                                </span>
                                                @if($event->end_date)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ __('to :date', ['date' => $event->end_date->format('M d, Y H:i')]) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Event Content -->
                                        <flux:text class="font-medium text-gray-900 dark:text-gray-100">{{ $event->title }}</flux:text>
                                        @if($event->description)
                                            <flux:text class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $event->description }}</flux:text>
                                        @endif
                                        @if($event->location)
                                            <flux:text class="text-gray-500 dark:text-gray-500 text-sm mt-1">{{ __('Location: :location', ['location' => $event->location]) }}</flux:text>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @else
            <flux:text class="text-gray-500 dark:text-gray-400 text-center py-8">
                {{ __('No history or notes yet.') }}
            </flux:text>
        @endif
    </flux:container>

    <!-- Action Buttons -->
    <flux:container>
        <div class="flex space-x-4 mt-6">
            <flux:button href="{{ route('people.index') }}" variant="outline" icon="arrow-left">
                {{ __('Back to People') }}
            </flux:button>
        </div>
    </flux:container>
</div>
