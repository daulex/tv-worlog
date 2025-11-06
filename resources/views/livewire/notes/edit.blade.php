<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Note</h1>
        <flux:button href="{{ route('notes.index') }}">Back</flux:button>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Note Type</flux:label>
                <flux:select wire:model="note_type" required wire:change="$refresh">
                    <option value="">Select a type</option>
                    <option value="person" {{ $note_type == 'person' ? 'selected' : '' }}>Person</option>
                    <option value="client" {{ $note_type == 'client' ? 'selected' : '' }}>Client</option>
                    <option value="vacancy" {{ $note_type == 'vacancy' ? 'selected' : '' }}>Vacancy</option>
                    <option value="equipment" {{ $note_type == 'equipment' ? 'selected' : '' }}>Equipment</option>
                </flux:select>
                <flux:error name="note_type" />
            </flux:field>

            <flux:field>
                <flux:label>Related To</flux:label>
                <flux:select wire:model="entity_id" required>
                    <option value="">Select an item</option>
                    @if ($note_type == 'person')
                        @foreach ($people as $person)
                            <option value="{{ $person->id }}" {{ $entity_id == $person->id ? 'selected' : '' }}>{{ $person->name }}</option>
                        @endforeach
                    @elseif ($note_type == 'client')
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" {{ $entity_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    @elseif ($note_type == 'vacancy')
                        @foreach ($vacancies as $vacancy)
                            <option value="{{ $vacancy->id }}" {{ $entity_id == $vacancy->id ? 'selected' : '' }}>{{ $vacancy->title }}</option>
                        @endforeach
                    @elseif ($note_type == 'equipment')
                        @foreach ($equipment as $equipment)
                            <option value="{{ $equipment->id }}" {{ $entity_id == $equipment->id ? 'selected' : '' }}>{{ $equipment->name }}</option>
                        @endforeach
                    @endif
                </flux:select>
                <flux:error name="entity_id" />
            </flux:field>

            <flux:field class="md:col-span-2">
                <flux:label>Note Content</flux:label>
                <flux:textarea wire:model="note_text" rows="6" required placeholder="Enter your note here..."></flux:textarea>
                <flux:error name="note_text" />
            </flux:field>
        </div>

        <div class="mt-6 flex gap-3">
            <flux:button type="submit" variant="primary">Update</flux:button>
            <flux:button href="{{ route('notes.index') }}" variant="ghost">Cancel</flux:button>
        </div>
    </form>
</div>
