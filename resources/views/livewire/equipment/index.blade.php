<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Equipment</h1>
        <flux:button href="{{ route('equipment.create') }}">Add Equipment</flux:button>
    </div>

    @if (session()->has('message'))
        <flux:callout type="success" class="mb-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Search equipment..."
        class="mb-4"
    />

    <div class="w-full bg-white rounded-lg shadow overflow-hidden mb-5 border border-gray-200">
        <table class="w-full divide-y divide-gray-200 border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model</th>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial</th>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Holder</th>
                     <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Price</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($equipment as $item)
                    <tr>
                         <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><a href="{{ route('equipment.show', $item) }}" class="text-gray-900 hover:text-gray-900 hover:underline">{{ $item->brand }}</a></td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><a href="{{ route('equipment.show', $item) }}" class="text-gray-500 hover:text-gray-500 hover:underline">{{ $item->model }}</a></td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->serial }}</td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                             @if($item->currentHolder)
                                 {{ $item->currentHolder->first_name }} {{ $item->currentHolder->last_name }}
                             @else
                                 -
                             @endif
                         </td>
                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€{{ number_format($item->purchase_price, 2, '.', ',') }}</td>
                    </tr>
                @empty
                     <tr>
                         <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No equipment found.</td>
                     </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $equipment->links() }}
</div>
