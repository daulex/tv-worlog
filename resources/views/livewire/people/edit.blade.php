<div>
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Person</h1>

        @include('livewire.partials.person-form', [
            'fieldPrefix' => '',
            'submitAction' => 'save',
            'submitText' => 'Update Person',
            'cancelUrl' => route('people.index'),
            'cancelText' => 'Cancel',
            'clients' => $clients,
            'vacancies' => $vacancies,
        ])
    </div>
</div>
