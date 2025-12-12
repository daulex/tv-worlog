<div>
    <flux:container>
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="xl">{{ $checklist->title }}</flux:heading>
            <div class="flex space-x-2">
                <flux:button href="{{ route('checklists.index') }}" variant="outline" icon="arrow-left">
                    Back to Checklists
                </flux:button>
                <flux:button href="{{ route('checklists.edit', $checklist) }}" variant="outline" icon="pencil">
                    Edit
                </flux:button>
                <flux:button
                    wire:click="delete"
                    wire:confirm="Are you sure you want to delete this checklist? This action cannot be undone."
                    variant="outline"
                    class="text-red-600 hover:text-red-700"
                    icon="trash"
                >
                    Delete
                </flux:button>
            </div>
        </div>

        @if($checklist->description)
            <div class="mb-6">
                <flux:text class="text-gray-600 dark:text-gray-400">
                    {{ $checklist->description }}
                </flux:text>
            </div>
        @endif

        <!-- Items -->
        <flux:fieldset legend="Checklist Items ({{ $checklist->items->count() }})">
            @if($checklist->items->count() > 0)
                <div class="space-y-4">
                    @foreach($checklist->items as $item)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <flux:badge variant="{{ $item->required ? 'danger' : 'outline' }}">
                                        {{ ucfirst($item->type) }}
                                        @if($item->required)
                                            *
                                        @endif
                                    </flux:badge>
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $item->label }}
                                    </span>
                                </div>
                                @if($item->required)
                                    <flux:text class="text-sm text-red-600 dark:text-red-400 mt-1">
                                        Required
                                    </flux:text>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Order: {{ $item->order + 1 }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <flux:text class="text-gray-500 dark:text-gray-400 text-center py-8">
                    No items in this checklist.
                </flux:text>
            @endif
        </flux:fieldset>

        <!-- Usage Stats -->
        <flux:fieldset legend="Usage Statistics">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $checklist->instances()->count() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Total Instances
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $checklist->instances()->whereNotNull('completed_at')->count() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Completed
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $checklist->instances()->whereNull('completed_at')->count() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        In Progress
                    </div>
                </div>
            </div>
        </flux:fieldset>
    </flux:container>
</div>
