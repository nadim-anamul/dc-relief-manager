<x-main-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Audit Log Details') }}</h1>
			<div class="flex space-x-3">
				<a href="{{ route('admin.audit-logs.index') }}" class="btn-secondary">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
					</svg>
					{{ __('Back to Logs') }}
				</a>
			</div>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Basic Information -->
		<div class="card p-6">
			<h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Basic Information') }}</h3>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				<div>
					<label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Event Type') }}</label>
					<div class="mt-1">
						<span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $auditLog->event_badge_class }}">
							{{ $auditLog->event_display }}
						</span>
					</div>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Model') }}</label>
					<p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $auditLog->model_name }}</p>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Model ID') }}</label>
					<p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $auditLog->auditable_id }}</p>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('User') }}</label>
					<p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $auditLog->user->name ?? 'System' }}</p>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('IP Address') }}</label>
					<p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $auditLog->ip_address ?? '-' }}</p>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('User Agent') }}</label>
					<p class="mt-1 text-sm text-gray-900 dark:text-white break-all">{{ $auditLog->user_agent ?? '-' }}</p>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('URL') }}</label>
					<p class="mt-1 text-sm text-gray-900 dark:text-white break-all">{{ $auditLog->url ?? '-' }}</p>
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Date & Time') }}</label>
					<p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $auditLog->created_at->format('M d, Y H:i:s') }}</p>
				</div>
			</div>
		</div>

		<!-- Changed Fields -->
		@if($auditLog->changed_fields)
			<div class="card p-6">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Changed Fields') }}</h3>
				<div class="flex flex-wrap gap-2">
					@foreach($auditLog->changed_fields as $field)
						<span class="inline-flex px-3 py-1 text-sm font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
							{{ $field }}
						</span>
					@endforeach
				</div>
			</div>
		@endif

		<!-- Old Values -->
		@if($auditLog->old_values)
			<div class="card p-6">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Previous Values') }}</h3>
				<div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
					<pre class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
				</div>
			</div>
		@endif

		<!-- New Values -->
		@if($auditLog->new_values)
			<div class="card p-6">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('New Values') }}</h3>
				<div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
					<pre class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
				</div>
			</div>
		@endif

		<!-- Remarks -->
		@if($auditLog->remarks)
			<div class="card p-6">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Remarks') }}</h3>
				<div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
					<p class="text-sm text-gray-900 dark:text-white">{{ $auditLog->remarks }}</p>
				</div>
			</div>
		@endif

		<!-- Related Model Information -->
		@if($auditLog->auditable)
			<div class="card p-6">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">{{ __('Related Model Information') }}</h3>
				<div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
					<pre class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap">{{ json_encode($auditLog->auditable->toArray(), JSON_PRETTY_PRINT) }}</pre>
				</div>
			</div>
		@endif
	</div>
</x-main-layout>
