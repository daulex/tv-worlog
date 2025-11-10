@props(['label', 'value', 'isEditing', 'field', 'isUnassigned'])

@if (!$isEditing)
    <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
        <flux:text class="font-medium text-gray-900 dark:text-gray-100">{{ $label }}</flux:text>
        <div class="text-right">
            @if($value)
                @if(str_starts_with($value, 'http://') || str_starts_with($value, 'https://'))
                    <flux:link href="{{ $value }}" target="_blank" rel="noopener noreferrer">{{ $value }}</flux:link>
                @else
                    <flux:text>{{ $value }}</flux:text>
                @endif
            @elseif(isset($isUnassigned) && $isUnassigned)
                <flux:text class="text-gray-400 italic">{{ __('Unassigned') }}</flux:text>
            @else
                <flux:text class="text-gray-400">—</flux:text>
            @endif
        </div>
    </div>
@endif