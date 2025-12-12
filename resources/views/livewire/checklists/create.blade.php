<div>
    <flux:container>
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="xl">Create Checklist</flux:heading>
            <flux:button href="{{ route('checklists.index') }}" variant="outline" icon="arrow-left">
                Back to Checklists
            </flux:button>
        </div>

        <form wire:submit="save" class="space-y-6">
            <!-- Title -->
            <flux:field>
                <flux:label>Title</flux:label>
                <flux:input wire:model="title" required />
                @error('title')
                    <flux:text color="red">{{ $message }}</flux:text>
                @enderror
            </flux:field>

            <!-- Description -->
            <flux:field>
                <flux:label>Description</flux:label>
                <flux:textarea wire:model="description" rows="3" placeholder="Optional description of the checklist" />
                @error('description')
                    <flux:text color="red">{{ $message }}</flux:text>
                @enderror
            </flux:field>

            <!-- Items -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <flux:heading level="3" size="lg">Checklist Items</flux:heading>
                    <flux:button wire:click="addItem" variant="outline" size="sm" icon="plus">
                        Add Item
                    </flux:button>
                </div>

                @foreach($items as $index => $item)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="font-medium text-gray-900 dark:text-white">Item {{ $index + 1 }}</h4>
                            <div class="flex items-center space-x-2">
                                @if($index > 0)
                                    <flux:button wire:click="moveItemUp({{ $index }})" variant="ghost" size="sm" icon="chevron-up">
                                    </flux:button>
                                @endif
                                @if($index < count($items) - 1)
                                    <flux:button wire:click="moveItemDown({{ $index }})" variant="ghost" size="sm" icon="chevron-down">
                                    </flux:button>
                                @endif
                                @if(count($items) > 1)
                                    <flux:button wire:click="removeItem({{ $index }})" variant="ghost" size="sm" class="text-red-600" icon="trash">
                                    </flux:button>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Type -->
                            <flux:field>
                                <flux:label>Type</flux:label>
                                <flux:select wire:model="items.{{ $index }}.type">
                                    <flux:select.option value="bool">Checkbox (Boolean)</flux:select.option>
                                    <flux:select.option value="text">Text Input</flux:select.option>
                                    <flux:select.option value="number">Number Input</flux:select.option>
                                    <flux:select.option value="textarea">Textarea</flux:select.option>
                                </flux:select>
                            </flux:field>

                            <!-- Required -->
                            <flux:field>
                                <flux:checkbox wire:model="items.{{ $index }}.required">
                                    Required
                                </flux:checkbox>
                            </flux:field>
                        </div>

                        <!-- Label -->
                        <flux:field>
                            <flux:label>Label</flux:label>
                            <flux:input wire:model="items.{{ $index }}.label" placeholder="Enter item label" required />
                            @error("items.{$index}.label")
                                <flux:text color="red">{{ $message }}</flux:text>
                            @enderror
                        </flux:field>
                    </div>
                @endforeach

                @error('items')
                    <flux:text color="red">{{ $message }}</flux:text>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4">
                <flux:button href="{{ route('checklists.index') }}" variant="outline">
                    Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Create Checklist
                </flux:button>
            </div>
        </form>
    </flux:container>
</div>
