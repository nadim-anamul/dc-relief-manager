@props([
	'action' => '',
	'method' => 'GET',
	'hasFilters' => false,
	'resetUrl' => null,
])

<x-section-card>
	<x-slot name="header">
		<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
			<h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">{{ __('Filters') }}</h3>
			@if($hasFilters && $resetUrl)
			<a href="{{ $resetUrl }}" 
				class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md transition-colors duration-200">
				<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
				</svg>
				{{ __('Clear Filters') }}
			</a>
			@endif
		</div>
	</x-slot>

	<form method="{{ $method }}" action="{{ $action }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
		{{ $slot }}
		
		<div class="sm:col-span-2 lg:col-span-3 xl:col-span-4 flex justify-end pt-2">
			<button type="submit" class="inline-flex items-center px-4 sm:px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md w-full sm:w-auto justify-center">
				<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
				</svg>
				{{ __('Apply Filters') }}
			</button>
		</div>
	</form>
</x-section-card>

