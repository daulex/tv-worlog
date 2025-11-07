@props(['label', 'name', 'options', 'placeholder', 'isEditing'])

@if ($isEditing)
    <flux:field>
        <flux:label>{{ $label }}</flux:label>
        <flux:select wire:model="{{ $name }}">
            <option value="">{{ $placeholder ?? 'Select...' }}</option>
            @foreach($options as $option)
                <option value="{{ $option->value ?? $option->id }}">{{ $option->label ?? $option->name }}</option>
            @endforeach
        </flux:select>
        <flux:error name="{{ $name }}" />
    </flux:field>
@endif