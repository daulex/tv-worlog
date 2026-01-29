<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Reimbursements</h1>
        <flux:button wire:click="showForm" variant="primary">
            Add Reimbursement
        </flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    <!-- Add/Edit Form -->
    @if($showingForm)
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-medium mb-4">{{ $editingId ? 'Edit' : 'Add' }} Reimbursement</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:field>
                        <flux:label for="client_id">Client</flux:label>
                        <flux:select wire:model="client_id" id="client_id" placeholder="Select client">
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="client_id" />
                    </flux:field>
                </div>
                
                <div>
                    <flux:field>
                        <flux:label for="amount">Amount</flux:label>
                        <flux:input wire:model="amount" id="amount" type="number" step="0.01" placeholder="0.00" />
                        <flux:error name="amount" />
                    </flux:field>
                </div>
                
                <div class="md:col-span-2">
                    <flux:field>
                        <flux:label for="name">Name</flux:label>
                        <flux:input wire:model="name" id="name" placeholder="e.g., Adobe License, Hosting, etc." />
                        <flux:error name="name" />
                    </flux:field>
                </div>
                
                <div class="md:col-span-2">
                    <flux:field>
                        <flux:label for="notes">Notes (optional)</flux:label>
                        <flux:textarea wire:model="notes" id="notes" rows="2" placeholder="Optional notes..." />
                        <flux:error name="notes" />
                    </flux:field>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-4">
                <flux:button wire:click="hideForm" variant="ghost">Cancel</flux:button>
                <flux:button wire:click="save" variant="primary">
                    {{ $editingId ? 'Update' : 'Save' }}
                </flux:button>
            </div>
        </div>
    @endif

    <!-- Reimbursements Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        @if($reimbursements->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Client</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Notes</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($reimbursements as $reimbursement)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-gray-900">{{ $reimbursement->client->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('reimbursements.show', $reimbursement) }}" class="font-medium text-blue-600 hover:text-blue-500 hover:underline">
                                        {{ $reimbursement->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-gray-900">${{ number_format($reimbursement->amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $reimbursement->notes ? Str::limit($reimbursement->notes, 50) : '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $reimbursement->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <flux:button size="sm" variant="ghost" wire:click="edit({{ $reimbursement->id }})">
                                            Edit
                                        </flux:button>
                                        <flux:button size="sm" variant="ghost" wire:click="delete({{ $reimbursement->id }})" 
                                                   wire:confirm="Are you sure you want to delete this reimbursement?">
                                            Delete
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <p class="text-gray-500 mb-4">No reimbursements yet.</p>
                <flux:button wire:click="showForm" variant="primary">
                    Add First Reimbursement
                </flux:button>
            </div>
        @endif
    </div>
</div>