<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.organization-types.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ localized_attr($organizationType,'name') }}</h1>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Organization Type Details -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
			<h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Organization Type Information') }}</h3>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					<div>
					<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Name') }}</dt>
					<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ localized_attr($organizationType,'name') }}</dd>
					</div>
					<div>
					<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created') }}</dt>
					<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ app()->isLocale('bn') ? bn_date($organizationType->created_at, 'j F, Y') : ($organizationType->created_at?->format('M d, Y')) }}</dd>
					</div>
					<div class="sm:col-span-2">
					<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							@if($organizationType->description)
								<div class="prose dark:prose-invert max-w-none">
								<p class="whitespace-pre-wrap">{{ localized_attr($organizationType,'description') }}</p>
								</div>
							@else
								<span class="text-gray-500 dark:text-gray-400">{{ __('No description provided') }}</span>
							@endif
						</dd>
					</div>
					<div>
					<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}</dt>
					<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ app()->isLocale('bn') ? bn_date($organizationType->updated_at, 'j F, Y') : ($organizationType->updated_at?->format('M d, Y')) }}</dd>
					</div>
				</dl>
			</div>
		</div>

		<!-- Actions -->
		<div class="flex justify-end space-x-3">
			<a href="{{ route('admin.organization-types.index') }}" class="btn-secondary">
				{{ __('Back to Organization Types') }}
			</a>
			<a href="{{ route('admin.organization-types.edit', $organizationType) }}" class="btn-primary">
				{{ __('Edit Organization Type') }}
			</a>
		</div>
	</div>
</x-main-layout>
