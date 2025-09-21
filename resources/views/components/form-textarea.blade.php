@props([
    'label' => null,
    'required' => false,
    'error' => null,
    'help' => null,
    'placeholder' => null,
    'disabled' => false,
    'readonly' => false,
    'rows' => 3,
])

@php
$textareaClasses = 'block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200 resize-vertical';
$errorClasses = 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500';
$disabledClasses = 'bg-gray-50 text-gray-500 cursor-not-allowed';
$readonlyClasses = 'bg-gray-50 text-gray-900 cursor-default';

if ($error) {
    $textareaClasses .= ' ' . $errorClasses;
}
if ($disabled) {
    $textareaClasses .= ' ' . $disabledClasses;
}
if ($readonly) {
    $textareaClasses .= ' ' . $readonlyClasses;
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

    <textarea 
        {{ $attributes->merge(['class' => $textareaClasses, 'rows' => $rows])->except(['label', 'error', 'help']) }}
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
    >{{ $attributes->get('value') }}</textarea>

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
