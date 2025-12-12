<div>
    <flux:container>
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="xl">Checklists</flux:heading>
            <flux:button href="{{ route('checklists.create') }}" variant="primary" icon="plus">
                Create Checklist
            </flux:button>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <flux:field>
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search checklists..."
                    type="search"
                />
            </flux:field>
        </div>

        <!-- Checklists List -->
        @if($checklists->count() > 0)
            <div class="space-y-4">
                @foreach($checklists as $checklist)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $checklist->title }}
                                </h3>
                                @if($checklist->description)
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $checklist->description }}
                                    </p>
                                @endif
                                <div class="flex items-center space-x-4 mt-2">
                                    <flux:badge variant="outline">
                                        {{ $checklist->items_count }} items
                                    </flux:badge>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        Created {{ $checklist->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <flux:button href="{{ route('checklists.show', $checklist) }}" variant="outline" size="sm">
                                    View
                                </flux:button>
                                <flux:button href="{{ route('checklists.edit', $checklist) }}" variant="outline" size="sm">
                                    Edit
                                </flux:button>
                                <flux:button
                                    wire:click="delete({{ $checklist->id }})"
                                    wire:confirm="Are you sure you want to delete this checklist?"
                                    variant="outline"
                                    size="sm"
                                    class="text-red-600 hover:text-red-700"
                                >
                                    Delete
                                </flux:button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $checklists->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <flux:icon name="document-check" class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No checklists found</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    @if($search)
                        No checklists match your search criteria.
                    @else
                        Get started by creating your first checklist.
                    @endif
                </p>
                <flux:button href="{{ route('checklists.create') }}" variant="primary">
                    Create Checklist
                </flux:button>
            </div>
        @endif
    </flux:container>
</div>
