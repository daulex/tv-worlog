<flux:container>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Create Person</h1>
        <flux:button href="{{ route('people.index') }}">Back</flux:button>
    </div>

    @include('livewire.partials.person-form', [
        'submitAction' => 'save',
        'submitText' => 'Create',
        'cancelUrl' => route('people.index'),
        'cancelText' => 'Cancel',
        'clients' => $clients,
        'vacancies' => $vacancies,
        'showDeleteButton' => false,
        'errors' => $this->getErrorBag(),
    ])
</flux:container>