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
                        <flux:button wire:click="unretireEquipment" variant="outline" size="sm">
                            <flux:icon name="arrow-path" class="w-4 h-4 mr-2" />
                            {{ __('Return to Service') }}
                        </flux:button>
                    @else
                        <flux:button wire:click="toggleRetireForm" variant="outline" size="sm">
                            <flux:icon name="archive-box-x-mark" class="w-4 h-4 mr-2" />
                            {{ __('Retire') }}
                        </flux:button>
                    @endif
                    <flux:button wire:click="toggleEditMode" variant="outline" size="sm">
                        <flux:icon name="pencil" class="w-4 h-4 mr-2" />
                        {{ __('Edit') }}
                    </flux:button>
                </div>
            @endif
        </div>
        
        @if(!$isEditing)
            <!-- View Mode -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <flux:field label="{{ __('Brand') }}">
                        <flux:text>{{ $equipment->brand }}</flux:text>
                    </flux:field>
                    
                    <flux:field label="{{ __('Model') }}">
                        <flux:text>{{ $equipment->model }}</flux:text>
                    </flux:field>
                    
                    <flux:field label="{{ __('Serial Number') }}">
                        <flux:text>{{ $equipment->serial }}</flux:text>
                    </flux:field>
                </div>
                
                <div class="space-y-4">
                    <flux:field label="{{ __('Purchase Date') }}">
                        <flux:text>{{ $equipment->purchase_date->format('M d, Y') }}</flux:text>
                    </flux:field>
                    
                    <flux:field label="{{ __('Purchase Price') }}">
                        <flux:text>${{ number_format($equipment->purchase_price, 2) }}</flux:text>
                    </flux:field>
                    
                    <flux:field label="{{ __('Current Owner') }}">
                        @if($equipment->currentOwner)
                            <flux:text>{{ $equipment->currentOwner->full_name }}</flux:text>
                        @else
                            <flux:text class="text-gray-500">{{ __('Unassigned') }}</flux:text>
                        @endif
                    </flux:field>
                </div>
            </div>
        @else
            <!-- Edit Mode -->
            <form wire:submit="saveEquipment">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <flux:field label="{{ __('Brand') }}">
                            <flux:input 
                                wire:model="editForm.brand" 
                                type="text"
                                required
                            />
                            @error('editForm.brand')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </flux:field>
                        
                        <flux:field label="{{ __('Model') }}">
                            <flux:input 
                                wire:model="editForm.model" 
                                type="text"
                                required
                            />
                            @error('editForm.model')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </flux:field>
                        
                        <flux:field label="{{ __('Serial Number') }}">
                            <flux:input 
                                wire:model="editForm.serial" 
                                type="text"
                                required
                            />
                            @error('editForm.serial')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </flux:field>
                    </div>
                    
                    <div class="space-y-4">
                        <flux:field label="{{ __('Purchase Date') }}">
                            <flux:input 
                                wire:model="editForm.purchase_date" 
                                type="date"
                                required
                            />
                            @error('editForm.purchase_date')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </flux:field>
                        
                        <flux:field label="{{ __('Purchase Price') }}">
                            <flux:input 
                                wire:model="editForm.purchase_price" 
                                type="number"
                                step="0.01"
                                min="0"
                                required
                            />
                            @error('editForm.purchase_price')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </flux:field>
                        
                        <flux:field label="{{ __('Current Owner') }}">
                            <flux:select wire:model="editForm.current_owner_id">
                                <option value="">{{ __('Select Owner') }}</option>
                                @foreach($this->people as $person)
                                    <option value="{{ $person->id }}">{{ $person->full_name }}</option>
                                @endforeach
                            </flux:select>
                            @error('editForm.current_owner_id')
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </flux:field>
                    </div>
                </div>
                
                <div class="flex space-x-4 mt-6">
                    <flux:button type="submit" variant="primary">
                        <flux:icon name="check" class="w-4 h-4 mr-2" />
                        {{ __('Save Changes') }}
                    </flux:button>
                    
                    <flux:button wire:click="cancelEdit" variant="outline" type="button">
                        <flux:icon name="x-mark" class="w-4 h-4 mr-2" />
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
            <flux:heading level="2">{{ __('Retire Equipment') }}</flux:heading>
            
            <flux:callout variant="warning">
                {{ __('Retiring equipment will remove it from active service and unassign it from any current owner. This action can be reversed later.') }}
            </flux:callout>
            
            <form wire:submit="retireEquipment">
                <flux:field label="{{ __('Retirement Notes') }}">
                    <flux:textarea 
                        wire:model="retirementNotes" 
                        placeholder="{{ __('Why is this equipment being retired?') }}"
                        rows="3"
                        required
                    />
                    @error('retirementNotes')
                        <flux:text color="red">{{ $message }}</flux:text>
                    @enderror
                </flux:field>
                
                <div class="flex space-x-4 mt-4">
                    <flux:button type="submit" variant="danger">
                        <flux:icon name="archive-box-x-mark" class="w-4 h-4 mr-2" />
                        {{ __('Retire Equipment') }}
                    </flux:button>
                    
                    <flux:button wire:click="toggleRetireForm" variant="outline" type="button">
                        <flux:icon name="x-mark" class="w-4 h-4 mr-2" />
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </form>
        </flux:container>
    @endif

    <!-- Equipment History Timeline -->
    <flux:container>
        <flux:heading level="2">{{ __('Equipment History') }}</flux:heading>
        
        @if($equipment->equipmentHistory->count() > 0)
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gray-200 dark:bg-gray-700"></div>
                
                <!-- Timeline Items -->
                <div class="space-y-6">
                    @foreach($equipment->equipmentHistory->sortBy('change_date') as $history)
                        <div class="relative flex items-start space-x-4">
                            <!-- Timeline Icon -->
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-{{ $history->action_type_color }}-100 dark:bg-{{ $history->action_type_color }}-900 flex items-center justify-center">
                                <flux:icon :name="$history->action_type_icon" class="w-5 h-5 text-{{ $history->action_type_color }}-600 dark:text-{{ $history->action_type_color }}-400" />
                            </div>
                            
                            <!-- Timeline Content -->
                            <div class="flex-1 min-w-0">
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            <flux:badge variant="outline" class="bg-{{ $history->action_type_color }}-50 text-{{ $history->action_type_color }}-700 border-{{ $history->action_type_color }}-200">
                                                {{ __(ucfirst($history->action_type)) }}
                                            </flux:badge>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $history->change_date->format('M d, Y') }}
                                            </span>
                                        </div>
                                        
                                        @if($history->performedBy)
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ __('by :name', ['name' => $history->performedBy->full_name]) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($history->owner)
                                        <div class="mb-2">
                                            <span class="font-medium">{{ __('Owner') }}:</span>
                                            <span class="ml-2">{{ $history->owner->full_name }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($history->notes)
                                        <div class="text-gray-700 dark:text-gray-300">
                                            <span class="font-medium">{{ __('Notes') }}:</span>
                                            <p class="mt-1">{{ $history->notes }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($history->action)
                                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                            <span class="font-medium">{{ __('Action') }}:</span>
                                            <span class="ml-2">{{ $history->action }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <flux:callout variant="info">
                {{ __('No history records found for this equipment.') }}
            </flux:callout>
        @endif
    </flux:container>

    <!-- Add Note Section -->
    <flux:container>
        <flux:heading level="2">{{ __('Add Note') }}</flux:heading>
        
        @if(!$showNoteForm)
            <flux:button wire:click="toggleNoteForm" variant="outline">
                <flux:icon name="plus" class="w-4 h-4 mr-2" />
                {{ __('Add Note') }}
            </flux:button>
        @else
            <form wire:submit="addNote">
                <flux:field label="{{ __('Note') }}">
                    <flux:textarea 
                        wire:model="newNote" 
                        placeholder="{{ __('Enter your note here...') }}"
                        rows="3"
                    />
                    @error('newNote')
                        <flux:text color="red">{{ $message }}</flux:text>
                    @enderror
                </flux:field>
                
                <div class="flex space-x-4 mt-4">
                    <flux:button type="submit" variant="primary">
                        <flux:icon name="check" class="w-4 h-4 mr-2" />
                        {{ __('Save Note') }}
                    </flux:button>
                    
                    <flux:button wire:click="toggleNoteForm" variant="outline" type="button">
                        <flux:icon name="x-mark" class="w-4 h-4 mr-2" />
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </form>
        @endif
    </flux:container>

    <!-- Action Buttons -->
    <flux:container>
        <div class="flex space-x-4">
            @if(!$isEditing)
                <flux:button href="{{ route('equipment.index') }}" variant="outline">
                    <flux:icon name="arrow-left" class="w-4 h-4 mr-2" />
                    {{ __('Back to Equipment') }}
                </flux:button>
            @endif
        </div>
    </flux:container>
</div>