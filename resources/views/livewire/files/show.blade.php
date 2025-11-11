<div>
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">File Details</h1>
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-2">
            <flux:button href="{{ route('files.download', $file) }}" variant="outline" class="w-full sm:w-auto">
                Download
            </flux:button>
            <flux:button href="{{ route('files.edit', $file) }}" variant="ghost" class="w-full sm:w-auto">
                Edit
            </flux:button>
            <flux:button href="{{ route('files.index') }}" class="w-full sm:w-auto">Back</flux:button>
        </div>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-6">
            {{ session('message') }}
        </flux:callout>
        @php
            session()->forget('message');
        @endphp
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- File Information -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">File Information</h2>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        @if ($file->isImage())
                            <img src="{{ $file->public_url }}" alt="{{ $file->filename }}" class="h-24 w-24 object-cover rounded-lg">
                        @else
                            <div class="h-24 w-24 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <span class="text-4xl">{{ $file->file_icon }}</span>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $file->filename }}</h3>
                            @if ($file->description)
                                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $file->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">File Type</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $file->file_type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">File Size</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $file->file_size_formatted }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Category</p>
                            <flux:badge variant="{{ $file->file_category === 'cv' ? 'primary' : ($file->file_category === 'contract' ? 'warning' : ($file->file_category === 'certificate' ? 'success' : 'secondary')) }}">
                                {{ ucfirst($file->file_category) }}
                            </flux:badge>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Uploaded</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $file->uploaded_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Preview -->
            @if ($file->isImage())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preview</h2>
                    <img src="{{ $file->public_url }}" alt="{{ $file->filename }}" class="w-full rounded-lg">
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Person Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Person</h2>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ $file->person->initials() }}
                    </div>
                    <div>
                        <flux:button href="{{ route('people.show', $file->person) }}" variant="ghost" class="p-0">
                            {{ $file->person->name }}
                        </flux:button>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $file->person->position }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:hidden">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h2>
                <div class="space-y-3">
                    <flux:button href="{{ route('files.download', $file) }}" class="w-full" variant="outline">
                        Download File
                    </flux:button>
                    <flux:button href="{{ route('files.edit', $file) }}" class="w-full" variant="ghost">
                        Edit Details
                    </flux:button>
                    <flux:button 
                        wire:click="delete" 
                        class="w-full" 
                        variant="danger"
                        wire:confirm="Are you sure you want to delete this file? This action cannot be undone."
                    >
                        Delete File
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>
