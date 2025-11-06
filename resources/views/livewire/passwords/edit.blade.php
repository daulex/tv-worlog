<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Password</h1>
        <flux:button href="{{ route('passwords.index') }}">Back</flux:button>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input wire:model="name" type="text" required />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>URL</flux:label>
                <flux:input wire:model="url" type="url" placeholder="https://example.com" />
                <flux:error name="url" />
            </flux:field>

            <flux:field>
                <flux:label>Username</flux:label>
                <flux:input wire:model="username" type="text" required />
                <flux:error name="username" />
            </flux:field>

            <flux:field>
                <flux:label>Password</flux:label>
                <flux:input wire:model="password_value" type="password" required />
                <flux:error name="password_value" />
            </flux:field>
        </div>

        <flux:field class="mt-6">
            <flux:label>Notes</flux:label>
            <flux:textarea wire:model="notes" rows="3" />
            <flux:error name="notes" />
        </flux:field>

        <div class="mt-6 flex gap-3">
            <flux:button type="submit" variant="primary">Update</flux:button>
            <flux:button href="{{ route('passwords.index') }}" variant="ghost">Cancel</flux:button>
        </div>
    </form>
</div>
