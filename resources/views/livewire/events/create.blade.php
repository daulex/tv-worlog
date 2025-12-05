<flux:container>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Create Event</h1>
        <flux:button href="{{ route('events.index') }}">Back</flux:button>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Title</flux:label>
                <flux:input wire:model="title" type="text" required />
                <flux:error name="title" />
            </flux:field>

            <flux:field>
                <flux:label>Type</flux:label>
                <flux:select wire:model="type" required>
                    <option value="">Select a type</option>
                    <option value="Meeting">Meeting</option>
                    <option value="Interview">Interview</option>
                    <option value="Training">Training</option>
                    <option value="Other">Other</option>
                </flux:select>
                <flux:error name="type" />
            </flux:field>

            <flux:field>
                <flux:label>Start Date</flux:label>
                <flux:input wire:model="start_date" type="datetime-local" required />
                <flux:error name="start_date" />
            </flux:field>

            <flux:field>
                <flux:label>End Date</flux:label>
                <flux:input wire:model="end_date" type="datetime-local" required />
                <flux:error name="end_date" />
            </flux:field>

            <flux:field>
                <flux:label>Location</flux:label>
                <flux:input wire:model="location" type="text" placeholder="Optional" />
                <flux:error name="location" />
            </flux:field>

            <flux:field class="md:col-span-2">
                <flux:label>Description</flux:label>
                <flux:textarea wire:model="description" rows="4" placeholder="Optional description"></flux:textarea>
                <flux:error name="description" />
            </flux:field>
        </div>

        <div class="mt-6 flex gap-3">
            <flux:button type="submit" variant="primary">Save</flux:button>
            <flux:button href="{{ route('events.index') }}" variant="outline">Cancel</flux:button>
        </div>
    </form>
</flux:container>
