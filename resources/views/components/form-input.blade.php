@props([
    'label' => null,
    'type' => 'text',
    'required' => false,
    'error' => null,
    'help' => null,
    'icon' => null,
    'placeholder' => null,
    'disabled' => false,
    'readonly' => false,
])

@php
$inputClasses = 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200';
$errorClasses = 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500';
$disabledClasses = 'bg-gray-50 text-gray-500 cursor-not-allowed';
$readonlyClasses = 'bg-gray-50 text-gray-900 cursor-default';

if ($error) {
    $inputClasses .= ' ' . $errorClasses;
}
if ($disabled) {
    $inputClasses .= ' ' . $disabledClasses;
}
if ($readonly) {
    $inputClasses .= ' ' . $readonlyClasses;
}
@endphp

<div class="space-y-1">
    @if($label)
        <label for="{{ $attributes->get('id') }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $icon !!}
                </svg>
            </div>
            <input 
                {{ $attributes->merge(['class' => $inputClasses . ' pl-10'])->except(['label', 'error', 'help', 'icon']) }}
                type="{{ $type }}"
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
            />
        @else
            <input 
                {{ $attributes->merge(['class' => $inputClasses])->except(['label', 'error', 'help', 'icon']) }}
                type="{{ $type }}"
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
            />
        @endif
    </div>

    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
            <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            {{ $error }}
        </p>
    @elseif($help)
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $help }}</p>
    @endif
</div>
