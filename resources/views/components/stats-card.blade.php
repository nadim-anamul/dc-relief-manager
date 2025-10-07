@props([
	'title' => '',
	'value' => '',
	'icon' => null,
	'iconColor' => 'blue',
	'description' => null,
	'trend' => null,
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

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 hover:shadow-md transition-all duration-200">
	<div class="flex items-center justify-between">
		<div class="flex items-center space-x-3 min-w-0 flex-1">
			@if($icon)
			<div class="p-2 sm:p-3 {{ $iconBg }} rounded-lg flex-shrink-0">
				<div class="{{ $iconText }} w-5 h-5 sm:w-6 sm:h-6">
					{!! $icon !!}
				</div>
			</div>
			@endif
			<div class="min-w-0 flex-1">
				<p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ $title }}</p>
				<p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-0.5 truncate">{{ $value }}</p>
				@if($description)
				<p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">{{ $description }}</p>
				@endif
			</div>
		</div>
		@if($trend)
		<div class="ml-2 flex-shrink-0">
			{{ $trend }}
		</div>
		@endif
	</div>
</div>

