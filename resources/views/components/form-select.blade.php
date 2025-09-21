@props([
    'label' => null,
    'required' => false,
    'error' => null,
    'help' => null,
    'placeholder' => 'Select an option',
    'disabled' => false,
    'options' => [],
])

@php
$selectClasses = 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200';
$errorClasses = 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500';
$disabledClasses = 'bg-gray-50 text-gray-500 cursor-not-allowed';

if ($error) {
    $selectClasses .= ' ' . $errorClasses;
}
if ($disabled) {
    $selectClasses .= ' ' . $disabledClasses;
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
        <select 
            {{ $attributes->merge(['class' => $selectClasses])->except(['label', 'error', 'help', 'options', 'placeholder']) }}
            @if($disabled) disabled @endif
        >
            <option value="">{{ $placeholder }}</option>
            @foreach($options as $value => $label)
                <option value="{{ $value }}" {{ $attributes->get('value') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        
        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
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
