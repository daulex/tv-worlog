<flux:container>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Create Client</h1>
        <flux:button href="{{ route('clients.index') }}">Back</flux:button>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" type="text" required />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="contact_email" type="email" required />
                <flux:error name="contact_email" />
            </flux:field>

            <flux:field>
                <flux:label>Phone</flux:label>
                <flux:input wire:model="contact_phone" type="tel" placeholder="+1 (555) 123-4567" />
                <flux:error name="contact_phone" />
            </flux:field>
        </div>

        <flux:field class="mt-6">
            <flux:label>Address</flux:label>
            <flux:textarea wire:model="address" rows="3" />
            <flux:error name="address" />
        </flux:field>

        <div class="mt-6 flex gap-3">
            <flux:button type="submit" variant="primary">Save</flux:button>
            <flux:button href="{{ route('clients.index') }}" variant="outline">Cancel</flux:button>
        </div>
    </form>
</flux:container>
