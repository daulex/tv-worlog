@props(['label', 'name', 'type', 'value', 'isEditing', 'step'])

@if ($isEditing)
    <flux:field>
        <flux:label>{{ $label }}</flux:label>
        <flux:input 
            type="{{ $type ?? 'text' }}" 
            wire:model="{{ $name }}" 
            step="{{ $step ?? 'any' }}"
        />
        <flux:error name="{{ $name }}" />
    </flux:field>
@endif