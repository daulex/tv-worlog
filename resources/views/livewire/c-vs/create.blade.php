<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Create CV</h1>
        <flux:button href="{{ route('c-vs.index') }}">Back</flux:button>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Person</flux:label>
                <flux:select wire:model="person_id" required>
                    <option value="">Select a person</option>
                    @foreach ($people as $person)
                        <option value="{{ $person->id }}">{{ $person->name }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="person_id" />
            </flux:field>

            <flux:field>
                <flux:label>Uploaded At</flux:label>
                <flux:input wire:model="uploaded_at" type="datetime-local" required />
                <flux:error name="uploaded_at" />
            </flux:field>

            <flux:field class="md:col-span-2">
                <flux:label>File Path/URL</flux:label>
                <flux:input wire:model="file_path_or_url" required placeholder="Enter file path or URL..." />
                <flux:error name="file_path_or_url" />
            </flux:field>
        </div>

        <div class="mt-6 flex gap-3">
            <flux:button type="submit" variant="primary">Save</flux:button>
            <flux:button href="{{ route('c-vs.index') }}" variant="ghost">Cancel</flux:button>
        </div>
    </form>
</div>
