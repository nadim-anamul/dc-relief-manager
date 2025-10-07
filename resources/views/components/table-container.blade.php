@props([
	'title' => null,
	'totalCount' => null,
	'currentRange' => null,
])

<x-section-card :padding="false">
	@if($title || isset($header))
	<x-slot name="header">
		@isset($header)
			{{ $header }}
		@else
			<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
				<div class="flex items-center space-x-3">
					<h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
					@if($totalCount)
					<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
						{{ $totalCount }} {{ __('total') }}
					</span>
					@endif
				</div>
				@if($currentRange)
				<div class="flex items-center space-x-2">
					<span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">{{ $currentRange }}</span>
				</div>
				@endif
				@isset($actions)
				<div class="flex flex-wrap items-center gap-2">
					{{ $actions }}
				</div>
				@endisset
			</div>
		@endisset
	</x-slot>
	@endif

	<div class="overflow-x-auto">
		<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
			{{ $slot }}
		</table>
	</div>
</x-section-card>

