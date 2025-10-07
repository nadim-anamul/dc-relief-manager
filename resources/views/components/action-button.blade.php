@props([
	'href' => null,
	'type' => 'button',
	'variant' => 'primary',
	'size' => 'md',
	'icon' => null,
	'iconPosition' => 'left',
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2';

$variantClasses = match($variant) {
	'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
	'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
	'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
	'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
	'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white focus:ring-yellow-500',
	'info' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500',
	'outline' => 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 focus:ring-blue-500',
	default => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
};

$sizeClasses = match($size) {
	'xs' => 'px-2.5 py-1.5 text-xs',
	'sm' => 'px-3 py-1.5 text-sm',
	'md' => 'px-4 py-2 text-sm',
	'lg' => 'px-6 py-3 text-base',
	'xl' => 'px-8 py-4 text-lg',
	default => 'px-4 py-2 text-sm',
};

$classes = $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses;
@endphp

@if($href)
	<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
		@if($icon && $iconPosition === 'left')
			<span class="w-4 h-4 {{ $slot->isEmpty() ? '' : 'mr-2' }}">{!! $icon !!}</span>
		@endif
		{{ $slot }}
		@if($icon && $iconPosition === 'right')
			<span class="w-4 h-4 {{ $slot->isEmpty() ? '' : 'ml-2' }}">{!! $icon !!}</span>
		@endif
	</a>
@else
	<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
		@if($icon && $iconPosition === 'left')
			<span class="w-4 h-4 {{ $slot->isEmpty() ? '' : 'mr-2' }}">{!! $icon !!}</span>
		@endif
		{{ $slot }}
		@if($icon && $iconPosition === 'right')
			<span class="w-4 h-4 {{ $slot->isEmpty() ? '' : 'ml-2' }}">{!! $icon !!}</span>
		@endif
	</button>
@endif

