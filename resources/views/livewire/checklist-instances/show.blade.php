<div>
    <flux:container>
        <div class="flex items-center justify-between mb-6">
            <div>
                <flux:heading size="xl">{{ $checklistInstance->checklist->title }}</flux:heading>
                <flux:text class="text-gray-600 dark:text-gray-400">
                    For {{ $checklistInstance->person->full_name }}
                </flux:text>
            </div>
            <flux:button href="{{ route('people.show', $checklistInstance->person) }}" variant="outline" icon="arrow-left">
                Back to Person
            </flux:button>
        </div>

        <!-- Progress -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <flux:text class="font-medium">Progress</flux:text>
                <flux:text class="text-sm text-gray-500">
                    {{ $checklistInstance->progress['completed'] }}/{{ $checklistInstance->progress['total'] }} completed
                </flux:text>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div
                    class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                    style="width: {{ $checklistInstance->progress['percentage'] }}%"
                ></div>
            </div>
            @if($checklistInstance->is_completed)
                <flux:badge variant="success" class="mt-2">Completed</flux:badge>
            @endif
        </div>

        <!-- Checklist Items -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($checklistInstance->checklist->items as $item)
                <flux:fieldset legend="{{ $item->label }}" class="h-fit">
                    <div class="space-y-4">
                        @switch($item->type)
                            @case('bool')
                                <flux:field>
                                    <flux:checkbox
                                        :checked="$itemValues[$item->id] ?? false"
                                        wire:click="toggleItem({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        class="transition-opacity"
                                        wire:loading.class="opacity-50"
                                    >
                                        {{ $item->label }}
                                        @if($item->required)
                                            <span class="text-red-500 ml-1">*</span>
                                        @endif
                                    </flux:checkbox>
                                </flux:field>
                                @break

                             @case('text')
                                 <flux:field>
                                     <flux:input
                                         wire:model.live="itemValues.{{ $item->id }}"
                                         wire:change="updateItem({{ $item->id }}, $event.target.value)"
                                         wire:loading.attr="disabled"
                                         placeholder="Enter {{ strtolower($item->label) }}"
                                         :required="$item->required"
                                         class="text-sm transition-opacity"
                                         wire:loading.class="opacity-50"
                                     />
                                     @if($item->required)
                                         <flux:text class="text-sm text-red-600">Required</flux:text>
                                     @endif
                                 </flux:field>
                                 @break

                             @case('number')
                                 <flux:field>
                                     <flux:input
                                         wire:model.live="itemValues.{{ $item->id }}"
                                         wire:change="updateItem({{ $item->id }}, $event.target.value)"
                                         wire:loading.attr="disabled"
                                         type="number"
                                         placeholder="Enter {{ strtolower($item->label) }}"
                                         :required="$item->required"
                                         class="text-sm transition-opacity"
                                         wire:loading.class="opacity-50"
                                     />
                                     @if($item->required)
                                         <flux:text class="text-sm text-red-600">Required</flux:text>
                                     @endif
                                 </flux:field>
                                 @break

                             @case('textarea')
                                 <flux:field>
                                     <flux:textarea
                                         wire:model.live="itemValues.{{ $item->id }}"
                                         wire:change="updateItem({{ $item->id }}, $event.target.value)"
                                         wire:loading.attr="disabled"
                                         rows="2"
                                         placeholder="Enter {{ strtolower($item->label) }}"
                                         :required="$item->required"
                                         class="text-sm transition-opacity"
                                         wire:loading.class="opacity-50"
                                     />
                                     @if($item->required)
                                         <flux:text class="text-sm text-red-600">Required</flux:text>
                                     @endif
                                 </flux:field>
                                 @break
                        @endswitch

                        @if($this->isItemCompleted($item->id))
                            <flux:badge variant="success" size="sm" class="mt-2">Completed</flux:badge>
                        @endif
                    </div>
                </flux:fieldset>
            @endforeach
        </div>

        <!-- Completion Status -->
        @if($checklistInstance->is_completed)
            <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                <div class="flex items-center">
                    <flux:icon name="check-circle" class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" />
                    <flux:text class="font-medium text-green-800 dark:text-green-200">
                        Checklist completed on {{ $checklistInstance->completed_at->format('M d, Y \a\t H:i') }}
                    </flux:text>
                </div>
            </div>
        @endif
    </flux:container>
</div>
