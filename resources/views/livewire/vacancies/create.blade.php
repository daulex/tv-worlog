<flux:container>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Create Vacancy</h1>
        <flux:button href="{{ route('vacancies.index') }}">Back</flux:button>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Title</flux:label>
                <flux:input wire:model="title" type="text" required />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label>Client</flux:label>
                <flux:select wire:model="client_id" required>
                    <option value="">Select a client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="client_id" />
            </flux:field>

            <flux:field>
                <flux:label>Status</flux:label>
                <flux:select wire:model="status" required>
                    <option value="Open">Open</option>
                    <option value="Closed">Closed</option>
                    <option value="Paused">Paused</option>
                </flux:select>
                <flux:error name="status" />
            </flux:field>

            <flux:field>
                <flux:label>Budget</flux:label>
                <flux:input wire:model="budget" type="number" step="0.01" min="0" placeholder="0.00" />
                <flux:error name="budget" />
            </flux:field>

            <flux:field>
                <flux:label>Date Opened</flux:label>
                <flux:input wire:model="date_opened" type="date" required />
                <flux:error name="date_opened" />
            </flux:field>

            <flux:field>
                <flux:label>Date Closed</flux:label>
                <flux:input wire:model="date_closed" type="date" />
                <flux:error name="date_closed" />
            </flux:field>
        </div>

        <flux:field class="mt-6">
            <flux:label>Description</flux:label>
            <flux:textarea wire:model="description" rows="4" />
            <flux:error name="description" />
        </flux:field>

        <div class="mt-6 flex gap-3">
            <flux:button type="submit" variant="primary">Save</flux:button>
            <flux:button href="{{ route('vacancies.index') }}" variant="outline">Cancel</flux:button>
        </div>
    </form>
</flux:container>
