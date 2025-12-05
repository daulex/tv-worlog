<flux:container>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Create Equipment</h1>
        <flux:button href="{{ route('equipment.index') }}">Back</flux:button>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Brand</flux:label>
                <flux:input wire:model="brand" type="text" required />
                <flux:error name="brand" />
            </flux:field>

            <flux:field>
                <flux:label>Model</flux:label>
                <flux:input wire:model="model" type="text" required />
                <flux:error name="model" />
            </flux:field>

            <flux:field>
                <flux:label>Serial Number</flux:label>
                <flux:input wire:model="serial" type="text" required />
                <flux:error name="serial" />
            </flux:field>

            <flux:field>
                <flux:label>Current Holder</flux:label>
                <flux:select wire:model="current_holder_id">
                    <option value="">Select a holder</option>
                    @foreach ($people as $person)
                        <option value="{{ $person->id }}">{{ $person->first_name }} {{ $person->last_name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="current_holder_id" />
            </flux:field>

            <flux:field>
                <flux:label>Purchase Date</flux:label>
                <flux:input wire:model="purchase_date" type="date" required />
                <flux:error name="purchase_date" />
            </flux:field>

            <flux:field>
                <flux:label>Purchase Price</flux:label>
                <flux:input wire:model="purchase_price" type="number" step="0.01" min="0" placeholder="0.00" required />
                <flux:error name="purchase_price" />
            </flux:field>
        </div>

        <div class="mt-6 flex gap-3">
            <flux:button type="submit" variant="primary">Save</flux:button>
            <flux:button href="{{ route('equipment.index') }}" variant="outline">Cancel</flux:button>
        </div>
    </form>
</flux:container>
