<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $reimbursement->name }}</h1>
            <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                <span>{{ $reimbursement->client->name }}</span>
                <span>•</span>
                <span>${{ number_format($reimbursement->amount, 2) }}</span>
            </div>
        </div>
        <div class="flex gap-3">
            <flux:button variant="ghost" href="{{ route('reimbursements.index') }}">
                Back to List
            </flux:button>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-medium mb-4">Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Client</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $reimbursement->client->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-medium">
                            ${{ number_format($reimbursement->amount, 2) }}
                        </dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $reimbursement->name }}</dd>
                    </div>
                    @if($reimbursement->notes)
                        <div class="col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reimbursement->notes }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $reimbursement->created_at->format('M j, Y g:i A') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $reimbursement->updated_at->format('M j, Y g:i A') }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <h2 class="text-lg font-medium mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <flux:button size="sm" variant="outline" class="w-full" wire:click="toggleNoteForm">
                        Add Note
                    </flux:button>
                    <a href="{{ route('reimbursements.index') }}?edit={{ $reimbursement->id }}" class="block">
                        <flux:button size="sm" variant="outline" class="w-full">
                            Edit Details
                        </flux:button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-medium">Activity & Notes</h2>
                    <flux:button size="sm" wire:click="toggleNoteForm">
                        Add Note
                    </flux:button>
                </div>
            </div>
            
            <div class="p-6">
                <!-- Add Note Form -->
                @if($showNoteForm)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Add a note</h3>
                        <div class="space-y-3">
                            <flux:field>
                                <flux:textarea wire:model="newNote" placeholder="Add your note here..." rows="3" />
                                <flux:error name="newNote" />
                            </flux:field>
                            <div class="flex gap-2">
                                <flux:button size="sm" wire:click="addNote">Save Note</flux:button>
                                <flux:button size="sm" variant="ghost" wire:click="toggleNoteForm">Cancel</flux:button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Timeline -->
                @php $timeline = $this->getTimeline(); @endphp
                
                @if($timeline->count() > 0)
                    <div class="relative">
                        <!-- Timeline line -->
                        <div class="absolute left-4 top-0 bottom-0 w-px bg-gray-200"></div>
                        
                        <div class="space-y-6">
                            @foreach($timeline as $item)
                                @if($item['type'] === 'note')
                                    @php $note = $item['data']; @endphp
                                    <div class="relative flex items-start">
                                        <!-- Timeline Icon -->
                                        <div class="absolute -left-4 flex-shrink-0 w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center ring-4 ring-white border-2 border-purple-200">
                                            <flux:icon.document-text class="w-4 h-4 text-purple-600" />
                                        </div>
                                        
                                        <!-- Timeline Content -->
                                        <div class="flex-1 min-w-0 ml-8">
                                            <div class="bg-white rounded-lg border border-gray-200 p-3">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="flex items-center space-x-2">
                                                        <flux:badge variant="outline" class="bg-purple-50 text-purple-700 border-purple-200 text-xs">
                                                            Note Added
                                                        </flux:badge>
                                                        <span class="text-xs text-gray-500">
                                                            {{ $note->created_at->format('M d, Y H:i') }}
                                                        </span>
                                                    </div>
                                                    <div class="flex gap-1">
                                                        <flux:button size="xs" variant="ghost" wire:click="editNote({{ $note->id }})">
                                                            Edit
                                                        </flux:button>
                                                        <flux:button size="xs" variant="ghost" wire:click="deleteNote({{ $note->id }})" 
                                                                   wire:confirm="Are you sure you want to delete this note?">
                                                            Delete
                                                        </flux:button>
                                                    </div>
                                                </div>
                                                <div class="prose prose-sm max-w-none">
                                                    @if($editingNote === $note->id)
                                                        <div class="space-y-3">
                                                            <flux:field>
                                                                <flux:textarea wire:model="noteEditForm.note_text" rows="3" />
                                                                <flux:error name="noteEditForm.note_text" />
                                                            </flux:field>
                                                            <div class="flex gap-2">
                                                                <flux:button size="sm" wire:click="saveNoteEdit">Save</flux:button>
                                                                <flux:button size="sm" variant="ghost" wire:click="cancelNoteEdit">Cancel</flux:button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <flux:text class="text-gray-700">{{ $note->note_text }}</flux:text>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($item['type'] === 'history')
                                    @php $history = $item['data']; @endphp
                                    <div class="relative flex items-start">
                                        <!-- Timeline Icon -->
                                        <div class="absolute -left-4 flex-shrink-0 w-8 h-8 rounded-full bg-{{ $history->action_type_color }}-100 flex items-center justify-center ring-4 ring-white border-2 border-{{ $history->action_type_color }}-200">
                                            <flux:icon :name="$history->action_type_icon" class="w-4 h-4 text-{{ $history->action_type_color }}-600" />
                                        </div>
                                        
                                        <!-- Timeline Content -->
                                        <div class="flex-1 min-w-0 ml-8">
                                            <div class="bg-white rounded-lg border border-gray-200 p-3">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="flex items-center space-x-2">
                                                        <flux:badge variant="outline" class="bg-{{ $history->action_type_color }}-50 text-{{ $history->action_type_color }}-700 border-{{ $history->action_type_color }}-200 text-xs">
                                                            {{ ucfirst(str_replace('_', ' ', $history->action_type)) }}
                                                        </flux:badge>
                                                        <span class="text-xs text-gray-500">
                                                            {{ $history->change_date->format('M d, Y H:i') }}
                                                        </span>
                                                        @if($history->performedBy)
                                                            <span class="text-xs text-gray-500">
                                                                by {{ $history->performedBy->first_name }} {{ $history->performedBy->last_name }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($history->notes)
                                                    <flux:text class="text-gray-700">{{ $history->notes }}</flux:text>
                                                @else
                                                    <flux:text class="text-gray-700">{{ $history->action }}</flux:text>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">No activity or notes yet.</p>
                        <flux:button wire:click="toggleNoteForm">
                            Add First Note
                        </flux:button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>