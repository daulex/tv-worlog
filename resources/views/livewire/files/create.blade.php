<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Upload File</h1>
        <flux:button href="{{ route('files.index') }}">Back</flux:button>
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
                <flux:label>Category</flux:label>
                <flux:select wire:model="file_category" required>
                    @foreach ($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="file_category" />
            </flux:field>

            <flux:field class="md:col-span-2">
                <flux:label>File</flux:label>
                <flux:input 
                    type="file" 
                    wire:model="file" 
                    required
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                />
                <flux:error name="file" />
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Supported formats: PDF, DOC, DOCX, JPG, PNG, GIF. Maximum size: 10MB
                </p>
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

        <!-- File Preview -->
        @if ($file)
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">File Preview</h3>
                <div class="flex items-center space-x-4">
                    @if (str_starts_with($file->getMimeType(), 'image/'))
                        <div class="h-20 w-20 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                            <flux:icon name="photo" class="w-8 h-8 text-gray-500" />
                        </div>
                    @else
                        <div class="h-20 w-20 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                            <flux:icon name="document" class="w-8 h-8 text-gray-500" />
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $file->getClientOriginalName() }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ number_format($file->getSize() / 1024, 2) }} KB
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-6 flex gap-3">
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Upload File</span>
                <span wire:loading>
                    <flux:icon name="loading" class="w-4 h-4 mr-2 animate-spin" />
                    Uploading...
                </span>
            </flux:button>
            <flux:button href="{{ route('files.index') }}" variant="ghost">Cancel</flux:button>
        </div>
    </form>
</div>
