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
</flux:container>
