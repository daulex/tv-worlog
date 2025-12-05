<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Clients</h1>
        <flux:button href="{{ route('clients.create') }}">Add Client</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Search clients..."
        class="mb-4"
    />

    <div class="w-full bg-white rounded-lg shadow overflow-hidden mb-5 border border-gray-200">
        <table class="w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                 @forelse ($clients as $client)
                     <tr>
                         <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                             <a href="{{ route('clients.edit', $client) }}" class="hover:underline">{{ $client->name }}</a>
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                             {{ $client->contact_email ?? '-' }}
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->contact_phone ?? '-' }}</td>
                     </tr>
                 @empty
                     <tr>
                         <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No clients found.</td>
                     </tr>
                 @endforelse
            </tbody>
        </table>
    </div>

    {{ $clients->links() }}
</div>
