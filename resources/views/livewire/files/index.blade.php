<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Files</h1>
        <flux:button href="{{ route('files.create') }}" variant="primary">
            <flux:icon name="plus" class="w-4 h-4 mr-2" />
            Add File
        </flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-6">
            {{ session('message') }}
        </flux:callout>
        @php
            session()->forget('message');
        @endphp
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <flux:field>
                <flux:label>Search</flux:label>
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search files..."
                />
            </flux:field>

            <flux:field>
                <flux:label>Category</flux:label>
                <flux:select wire:model.live="fileCategory">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Person</flux:label>
                <flux:select wire:model.live="personFilter">
                    <option value="">All People</option>
                    @foreach ($people as $person)
                        <option value="{{ $person->id }}">{{ $person->name }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Per Page</flux:label>
                <flux:select wire:model.live="perPage">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </flux:select>
            </flux:field>
        </div>

        @if ($search || $fileCategory || $personFilter)
            <div class="mt-4">
                <flux:button wire:click="clearFilters" variant="ghost" size="sm">
                    Clear Filters
                </flux:button>
            </div>
        @endif
    </div>

    <!-- Files Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        @if ($files->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                File
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Person
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Category
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Size
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Uploaded
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($files as $file)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $file->filename }}
                                        </div>
                                        @if ($file->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $file->description }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <flux:button 
                                        href="{{ route('people.show', $file->person_id) }}" 
                                        variant="ghost" 
                                        size="sm"
                                    >
                                        {{ $file->person->name }}
                                    </flux:button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <flux:badge variant="{{ $file->file_category === 'cv' ? 'primary' : ($file->file_category === 'contract' ? 'warning' : ($file->file_category === 'certificate' ? 'success' : 'secondary')) }}">
                                        {{ ucfirst($file->file_category) }}
                                    </flux:badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $file->file_size_formatted }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $file->uploaded_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                    <flux:button variant="ghost" size="sm" href="{{ route('files.show', $file) }}">
                                        View
                                    </flux:button>
                                    <flux:button variant="ghost" size="sm" href="{{ route('files.download', $file) }}">
                                        Download
                                    </flux:button>
                                    <flux:button variant="ghost" size="sm" href="{{ route('files.edit', $file) }}">
                                        Edit
                                    </flux:button>
                                    <flux:button variant="ghost" size="sm" wire:click="deleteFile({{ $file->id }})" wire:confirm="Are you sure you want to delete this file?">
                                        Delete
                                    </flux:button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $files->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <flux:icon name="document" class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No files found</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    @if ($search || $fileCategory || $personFilter)
                        Try adjusting your search or filters.
                    @else
                        Get started by uploading your first file.
                    @endif
                </p>
                @if (!$search && !$fileCategory && !$personFilter)
                    <flux:button href="{{ route('files.create') }}" variant="primary">
                        <flux:icon name="plus" class="w-4 h-4 mr-2" />
                        Add File
                    </flux:button>
                @endif
            </div>
        @endif
    </div>
</div>
