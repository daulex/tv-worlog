<div class="space-y-6">
    <!-- Equipment Details Header -->
    <flux:container>
        <flux:heading>{{ $equipment->brand }} {{ $equipment->model }}</flux:heading>
        <flux:text>{{ __('Equipment Details and History') }}</flux:text>
    </flux:container>

    <!-- Equipment Information -->
    <flux:container>
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <flux:heading level="2">{{ __('Equipment Information') }}</flux:heading>
                @if($equipment->isRetired())
                    <flux:badge variant="danger" class="bg-red-100 text-red-800 border-red-200">
                        <flux:icon name="archive-box-x-mark" class="w-3 h-3 mr-1" />
                        {{ __('Retired') }}
                    </flux:badge>
                @endif
            </div>
            @if(!$isEditing)
                <div class="flex space-x-2">
                    @if($equipment->isRetired())
                        <flux:button wire:click="unretireEquipment" variant="outline" size="sm" icon="arrow-path">
                            {{ __('Return to Service') }}
                        </flux:button>
                    @else
                        <flux:button wire:click="toggleRetireForm" variant="outline" size="sm" icon="archive-box-x-mark">
                            {{ __('Retire') }}
                        </flux:button>
                    @endif
                    <flux:button wire:click="toggleEditMode" variant="outline" size="sm" icon="pencil">
                        {{ __('Edit') }}
                    </flux:button>
                </div>
            @endif
        </div>
        
        @if(!$isEditing)
            <!-- View Mode -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    @include('livewire.partials.field-view', [
                        'label' => __('Brand'),
                        'value' => $equipment->brand,
                    ])
                    
                    @include('livewire.partials.field-view', [
                        'label' => __('Model'),
                        'value' => $equipment->model,
                    ])
                    
                    @include('livewire.partials.field-view', [
                        'label' => __('Serial Number'),
                        'value' => $equipment->serial,
                    ])
                </div>
                
                <div class="space-y-4">
                    @include('livewire.partials.field-view', [
                        'label' => __('Purchase Date'),
                        'value' => $equipment->purchase_date->format('M d, Y'),
                    ])
                    
                    @include('livewire.partials.field-view', [
                        'label' => __('Purchase Price'),
                        'value' => '$' . number_format($equipment->purchase_price, 2),
                    ])
                    
                    @include('livewire.partials.field-view', [
                        'label' => __('Current Owner'),
                        'value' => $equipment->currentOwner ? $equipment->currentOwner->full_name : __('Unassigned'),
                        'isUnassigned' => !$equipment->currentOwner,
                    ])
                </div>
            </div>
        @else
            <!-- Edit Mode -->
            <form wire:submit="saveEquipment">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        @include('livewire.partials.field-edit', [
                            'label' => __('Brand'),
                            'name' => 'editForm.brand',
                            'type' => 'text',
                            'value' => $editForm['brand'],
                            'required' => true,
                        ])
                        
                        @include('livewire.partials.field-edit', [
                            'label' => __('Model'),
                            'name' => 'editForm.model',
                            'type' => 'text',
                            'value' => $editForm['model'],
                            'required' => true,
                        ])
                        
                        @include('livewire.partials.field-edit', [
                            'label' => __('Serial Number'),
                            'name' => 'editForm.serial',
                            'type' => 'text',
                            'value' => $editForm['serial'],
                            'required' => true,
                        ])
                    </div>
                    
                    <div class="space-y-4">
                        @include('livewire.partials.field-edit', [
                            'label' => __('Purchase Date'),
                            'name' => 'editForm.purchase_date',
                            'type' => 'date',
                            'value' => $editForm['purchase_date'],
                            'required' => true,
                        ])
                        
                        @include('livewire.partials.field-edit', [
                            'label' => __('Purchase Price'),
                            'name' => 'editForm.purchase_price',
                            'type' => 'number',
                            'value' => $editForm['purchase_price'],
                            'required' => true,
                            'step' => '0.01',
                            'min' => '0',
                        ])
                        
                        @include('livewire.partials.field-select', [
                            'label' => __('Current Owner'),
                            'name' => 'editForm.current_owner_id',
                            'value' => $editForm['current_owner_id'],
                            'options' => $this->people,
                            'placeholder' => __('Select Owner'),
                        ])
                    </div>
                </div>
                
                <div class="flex space-x-4 mt-6">
                    <flux:button type="submit" variant="primary" icon="check">
                        {{ __('Save Changes') }}
                    </flux:button>
                    
                    <flux:button wire:click="cancelEdit" variant="outline" type="button" icon="x-mark">
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </form>
        @endif
    </flux:container>

    <!-- Retirement Section -->
    @if($equipment->isRetired())
        <flux:container>
            <flux:heading level="2">{{ __('Retirement Information') }}</flux:heading>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <flux:field label="{{ __('Retirement Date') }}">
                        <flux:text>{{ $equipment->retired_at->format('M d, Y') }}</flux:text>
                    </flux:field>
                </div>
                
                <div class="space-y-4">
                    @if($equipment->retirement_notes)
                        <flux:field label="{{ __('Retirement Notes') }}">
                            <flux:text>{{ $equipment->retirement_notes }}</flux:text>
                        </flux:field>
                    @endif
                </div>
            </div>
        </flux:container>
    @elseif($showRetireForm)
        <flux:container>
            <flux:heading level="2" class="mb-2">{{ __('Retire Equipment') }}</flux:heading>
            
            <flux:callout variant="warning">
                {{ __('Retiring equipment will remove it from active service and unassign it from any current owner. This action can be reversed later.') }}
            </flux:callout>
            
            <form wire:submit="retireEquipment">
                <flux:field label="{{ __('Retirement Notes') }}">
                    <flux:textarea 
                        wire:model="retirementNotes" 
                        placeholder="{{ __('Why is this equipment being retired?') }}"
                        rows="3"
                        class="mt-3"
                        required
                    />
                    @error('retirementNotes')
                        <flux:text color="red">{{ $message }}</flux:text>
                    @enderror
                </flux:field>
                
                <div class="flex space-x-4 mt-4">
                    <flux:button type="submit" variant="danger" icon="archive-box-x-mark">
                        {{ __('Retire Equipment') }}
                    </flux:button>
                    
                    <flux:button wire:click="toggleRetireForm" variant="outline" type="button" icon="x-mark">
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </form>
        </flux:container>
    @endif


    <!-- Add Note Section -->
    <flux:container>
        @if($showNoteForm)
            <flux:container class="bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 mb-4 py-5 rounded-lg">
                <flux:field label="{{ __('Add Note') }}">
                    <flux:textarea 
                        wire:model="newNote" 
                        placeholder="{{ __('Enter your note here...') }}"
                        rows="3"
                        required
                    />
                    @error('newNote')
                        <flux:text color="red">{{ $message }}</flux:text>
                    @enderror
                </flux:field>
                
                <div class="flex space-x-4 pt-3">
                    <flux:button wire:click="addNote" type="button" icon="plus">
                        {{ __('Add Note') }}
                    </flux:button>
                    
                    <flux:button wire:click="toggleNoteForm" variant="outline" type="button" icon="x-mark">
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </flux:container>
        @else
            <flux:button wire:click="toggleNoteForm" variant="outline" class="mb-4" icon="plus">
                {{ __('Add Note') }}
            </flux:button>
        @endif
    </flux:container>

    <!-- Unified Timeline -->
    <flux:container>
        <flux:heading level="2" class="mb-2">{{ __('Timeline & History') }}</flux:heading>
        
        
        
        <!-- Unified Timeline -->
        @php
            $timeline = $this->getTimeline();
        @endphp
        
        @if($timeline->count() > 0)
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                
                <!-- Timeline Items -->
                <div class="space-y-4">
                    @foreach($timeline as $item)
                        @if($item['type'] === 'note')
                            @php
                                $note = $item['data'];
                            @endphp
                            <div class="relative flex items-start space-x-3">
                                <!-- Timeline Icon -->
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <flux:icon name="document-text" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                                </div>
                                
                                <!-- Timeline Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center space-x-2">
                                                <flux:badge variant="outline" class="bg-blue-50 text-blue-700 border-blue-200 text-xs">
                                                    {{ __('Note') }}
                                                </flux:badge>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $note->created_at->format('M d, Y H:i') }}
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
                                            <flux:text>{{ $note->note_text }}</flux:text>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            @php
                                $history = $item['data'];
                            @endphp
                            <div class="relative flex items-start space-x-3">
                                <!-- Timeline Icon -->
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-{{ $history->action_type_color }}-100 dark:bg-{{ $history->action_type_color }}-900 flex items-center justify-center">
                                    <flux:icon :name="$history->action_type_icon" class="w-4 h-4 text-{{ $history->action_type_color }}-600 dark:text-{{ $history->action_type_color }}-400" />
                                </div>
                                
                                <!-- Timeline Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center space-x-2">
                                                <flux:badge variant="outline" class="bg-{{ $history->action_type_color }}-50 text-{{ $history->action_type_color }}-700 border-{{ $history->action_type_color }}-200 text-xs">
                                                    {{ __(ucfirst($history->action_type)) }}
                                                </flux:badge>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $history->change_date->format('M d, Y H:i') }}
                                                </span>
                                                @if($history->performedBy)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ __('by :name', ['name' => $history->performedBy->full_name]) }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <!-- History Management Buttons -->
                                            @if($editingHistory == $history->id)
                                                <flux:button wire:click="cancelHistoryEdit" variant="outline" size="sm" icon="x-mark">
                                                </flux:button>
                                            @else
                                                <div class="flex items-center space-x-1">
                                                    @if($this->canEditHistory($history))
                                                        <flux:button wire:click="editHistory({{ $history->id }})" variant="outline" size="sm" icon="pencil">
                                                        </flux:button>
                                                    @endif
                                                    @if($this->canDeleteHistory($history))
                                                        <flux:button wire:click="deleteHistory({{ $history->id }})" variant="outline" size="sm" class="text-red-600 hover:text-red-700" icon="trash">
                                                        </flux:button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- History Content -->
                                        @if($editingHistory == $history->id)
                                            <div class="mt-3 space-y-3">
                                                <flux:field>
                                                    <flux:input wire:model="historyEditForm.action" />
                                                    @error('historyEditForm.action')
                                                        <flux:text color="red">{{ $message }}</flux:text>
                                                    @enderror
                                                </flux:field>
                                                
                                                <flux:field>
                                                    <flux:textarea 
                                                        wire:model="historyEditForm.notes" 
                                                        rows="3"
                                                        required
                                                    />
                                                    @error('historyEditForm.notes')
                                                        <flux:text color="red">{{ $message }}</flux:text>
                                                    @enderror
                                                </flux:field>
                                                
                                                <div class="flex space-x-4">
                                                    <flux:button wire:click="saveHistoryEdit" type="button" size="sm" icon="check">
                                                        {{ __('Save') }}
                                                    </flux:button>
                                                    
                                                    <flux:button wire:click="cancelHistoryEdit" variant="outline" type="button" size="sm" icon="x-mark">
                                                        {{ __('Cancel') }}
                                                    </flux:button>
                                                </div>
                                            </div>
                                        @else
                                            @switch($history->action_type)
                                                @case('purchased')
                                                    <flux:text>{{ $history->change_date->format('M d, Y H:i') }} - Equipment purchased</flux:text>
                                                    @if($history->notes)
                                                        <flux:text class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $history->notes }}</flux:text>
                                                    @endif
                                                    @break
                                                    
                                                @case('assigned')
                                                    <flux:text>{{ $history->change_date->format('M d, Y H:i') }} - Transferred to {{ $history->owner?->full_name ?? 'Unassigned' }}</flux:text>
                                                    @break
                                                    
                                                @case('retired')
                                                    <flux:text>{{ $history->change_date->format('M d, Y H:i') }} - Equipment retired</flux:text>
                                                    @if($history->notes)
                                                        <flux:text class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $history->notes }}</flux:text>
                                                    @endif
                                                    @break
                                                    
                                                @default
                                                    <flux:text>{{ $history->action }} - {{ $history->change_date->format('M d, Y H:i') }}</flux:text>
                                                    @if($history->notes)
                                                        <flux:text class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $history->notes }}</flux:text>
                                                    @endif
                                                    @break
                                            @endswitch
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
        <div class="flex space-x-4">
            @if(!$isEditing)
                <flux:button href="{{ route('equipment.index') }}" variant="outline" icon="arrow-left">
                    {{ __('Back to Equipment') }}
                </flux:button>
            @endif
        </div>
    </flux:container>
</div>