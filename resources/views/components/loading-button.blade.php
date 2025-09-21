@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'disabled' => false,
    'icon' => null,
    'loadingText' => 'Loading...'
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
$variantClasses = [
    'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
    'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
    'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white focus:ring-yellow-500',
    'outline' => 'border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 focus:ring-blue-500',
    'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-700 focus:ring-gray-500',
];
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
    'xl' => 'px-8 py-4 text-lg',
];
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size]]) }}
    @if($loading || $disabled) disabled @endif
>
    @if($loading)
        <x-loading-spinner size="sm" color="white" class="mr-2" />
        {{ $loadingText }}
    @else
        @if($icon)
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        @endif
        {{ $slot }}
    @endif
</button>
