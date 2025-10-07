@props([
	'title' => null,
	'subtitle' => null,
	'padding' => true,
	'noBorder' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-xl shadow-sm ' . ($noBorder ? '' : 'border border-gray-200 dark:border-gray-700') . ' overflow-hidden']) }}>
	@if($title || $subtitle || isset($header))
	<div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
		@isset($header)
			{{ $header }}
		@else
			<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
				<div class="min-w-0 flex-1">
					@if($title)
					<h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white truncate">{{ $title }}</h3>
					@endif
					@if($subtitle)
					<p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $subtitle }}</p>
					@endif
				</div>
				@isset($actions)
				<div class="flex flex-wrap items-center gap-2">
					{{ $actions }}
				</div>
				@endisset
			</div>
		@endisset
	</div>
	@endif
	
	<div class="{{ $padding ? 'p-4 sm:p-6' : '' }}">
		{{ $slot }}
	</div>
</div>

