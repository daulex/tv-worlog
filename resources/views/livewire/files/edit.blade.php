<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit File</h1>
        <flux:button href="{{ route('files.show', $file) }}">Back</flux:button>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:field>
                <flux:label>Person</flux:label>
                <flux:select wire:model="person_id" required>
                    @foreach ($people as $person)
                        <option value="{{ $person->id }}" {{ $person->id == $person_id ? 'selected' : '' }}>
                            {{ $person->name }}
                        </option>
                    @endforeach
                </flux:select>
                <flux:error name="person_id" />
            </flux:field>

            <flux:field>
                <flux:label>Category</flux:label>
                <flux:select wire:model="file_category" required>
                    @foreach ($categories as $key => $label)
                        <option value="{{ $key }}" {{ $key == $file_category ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </flux:select>
                <flux:error name="file_category" />
            </flux:field>

            <flux:field class="md:col-span-2">
                <flux:label>Description</flux:label>
                <flux:textarea 
                    wire:model="description" 
                    placeholder="Enter a description for this file..."
                    rows="3"
                />
                <flux:error name="description" />
            </flux:field>

            <flux:field>
                <flux:label>Uploaded At</flux:label>
                <flux:input wire:model="uploaded_at" type="datetime-local" required />
                <flux:error name="uploaded_at" />
            </flux:field>
        </div>

        <!-- File Information (Read-only) -->
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">File Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Filename</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $file->filename }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">File Type</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $file->file_type }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">File Size</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $file->file_size_formatted }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <flux:button type="submit" variant="primary">
                Save Changes
            </flux:button>
            <flux:button href="{{ route('files.show', $file) }}" variant="ghost">Cancel</flux:button>
        </div>
    </form>
</div>
