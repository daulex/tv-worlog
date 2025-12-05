<flux:container>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Edit Person</h1>
        <flux:button href="{{ route('people.show', $person) }}">Back</flux:button>
    </div>

    @include('livewire.partials.person-form', [
        'fieldPrefix' => '',
        'submitAction' => 'save',
        'submitText' => 'Update',
        'cancelUrl' => route('people.show', $person),
        'cancelText' => 'Cancel',
        'clients' => $clients,
        'vacancies' => $vacancies,
        'showDeleteButton' => true,
    ])

    @if($deleteAttempted && $associatedEquipment->count() > 0)
        <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon name="exclamation-triangle" class="w-5 h-5 text-amber-400" />
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-800">
                        Associated Equipment
                    </h3>
                    <div class="mt-2 text-sm text-amber-700">
                        <p>This person has {{ $associatedEquipment->count() }} equipment item{{ Str::plural('', $associatedEquipment->count()) }} assigned. You must unassign them before deleting the person:</p>
                        <ul class="mt-2 space-y-1">
                            @foreach($associatedEquipment as $equipment)
                                <li class="flex items-center justify-between">
                                    <a href="{{ route('equipment.show', $equipment) }}" class="text-amber-800 hover:text-amber-900 underline">
                                        {{ $equipment->brand }} {{ $equipment->model }} ({{ $equipment->serial }})
                                    </a>
                                    <flux:button wire:click="unassignEquipment({{ $equipment->id }})" variant="outline" size="sm" class="ml-2">
                                        Unassign
                                    </flux:button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($deleteAttempted && $errors->has('delete'))
        <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon name="exclamation-triangle" class="w-5 h-5 text-red-400" />
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Cannot Delete Person
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        {{ $errors->first('delete') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</flux:container>
