@props([
	'title' => '',
	'description' => null,
	'icon' => null,
	'iconColor' => 'blue',
])

@php
$iconBg = match($iconColor) {
	'blue' => 'bg-blue-100 dark:bg-blue-900',
	'indigo' => 'bg-indigo-100 dark:bg-indigo-900',
	'green' => 'bg-green-100 dark:bg-green-900',
	'red' => 'bg-red-100 dark:bg-red-900',
	'yellow' => 'bg-yellow-100 dark:bg-yellow-900',
	'purple' => 'bg-purple-100 dark:bg-purple-900',
	'pink' => 'bg-pink-100 dark:bg-pink-900',
	default => 'bg-blue-100 dark:bg-blue-900',
};

$iconText = match($iconColor) {
	'blue' => 'text-blue-600 dark:text-blue-400',
	'indigo' => 'text-indigo-600 dark:text-indigo-400',
	'green' => 'text-green-600 dark:text-green-400',
	'red' => 'text-red-600 dark:text-red-400',
	'yellow' => 'text-yellow-600 dark:text-yellow-400',
	'purple' => 'text-purple-600 dark:text-purple-400',
	'pink' => 'text-pink-600 dark:text-pink-400',
	default => 'text-blue-600 dark:text-blue-400',
};
@endphp

<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
	<div class="flex items-center space-x-3">
		@if($icon)
		<div class="p-2 {{ $iconBg }} rounded-lg flex-shrink-0">
			{!! $icon !!}
		</div>
		@endif
		<div class="min-w-0 flex-1">
			<h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate">{{ $title }}</h1>
			@if($description)
			<p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $description }}</p>
			@endif
		</div>
	</div>
	@isset($actions)
	<div class="flex flex-wrap gap-2 sm:gap-3">
		{{ $actions }}
	</div>
	@endisset
</div>

