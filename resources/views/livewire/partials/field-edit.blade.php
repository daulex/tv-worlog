@props(['label', 'name', 'type', 'value', 'isEditing', 'step', 'options', 'placeholder'])

@if ($isEditing)
    <flux:field>
        <flux:label>{{ $label }}</flux:label>
        @if($type === 'select')
            <flux:select wire:model="{{ $name }}">
                @if($placeholder ?? false)
                    <flux:select.option value="">{{ $placeholder }}</flux:select.option>
                @endif
                @foreach($options ?? [] as $key => $option)
                    <flux:select.option value="{{ $key }}">{{ $option }}</flux:select.option>
                @endforeach
            </flux:select>
        @elseif($type === 'textarea')
            <flux:textarea 
                wire:model="{{ $name }}" 
                rows="3"
            />
        @else
            <flux:input 
                type="{{ $type ?? 'text' }}" 
                wire:model="{{ $name }}" 
                step="{{ $step ?? 'any' }}"
                placeholder="{{ $placeholder ?? '' }}"
            />
        @endif
        <flux:error name="{{ $name }}" />
    </flux:field>
@endif