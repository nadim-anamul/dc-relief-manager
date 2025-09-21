<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('relief-applications.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reliefApplication->subject }}</h1>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Application Status -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex justify-between items-center">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Application Status</h3>
					<span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $reliefApplication->status_badge_class }}">
						{{ $reliefApplication->status_display }}
					</span>
				</div>
			</div>
			<div class="p-6">
				<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Application Date</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->date->format('F d, Y') }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Submitted</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->created_at->format('F d, Y g:i A') }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->updated_at->format('F d, Y g:i A') }}</dd>
					</div>
				</div>
			</div>
		</div>

		<!-- Organization Information -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Organization Information</h3>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Organization Name</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->organization_name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Organization Type</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->organizationType->name ?? 'Not specified' }}</dd>
					</div>
					<div class="sm:col-span-2">
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Organization Address</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->organization_address }}</dd>
					</div>
				</dl>
			</div>
		</div>

		<!-- Location Information -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Location Information</h3>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Zilla (District)</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->zilla->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Upazila (Sub-district)</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->upazila->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Union</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->union->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ward</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->ward->name }}</dd>
					</div>
				</dl>
			</div>
		</div>

		<!-- Relief Information -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Relief Information</h3>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Subject</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->subject }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Relief Type</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							<div class="flex items-center">
								@if($reliefApplication->reliefType->color_code)
									<div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $reliefApplication->reliefType->color_code }}"></div>
								@endif
								{{ $reliefApplication->reliefType->name }}
							</div>
						</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount Requested</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold text-lg">{{ $reliefApplication->formatted_amount }}</dd>
					</div>
					<div class="sm:col-span-2">
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Details</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							<div class="prose dark:prose-invert max-w-none">
								<p class="whitespace-pre-wrap">{{ $reliefApplication->details }}</p>
							</div>
						</dd>
					</div>
				</dl>
			</div>
		</div>

		<!-- Applicant Information -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Applicant Information</h3>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Applicant Name</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Designation</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_designation ?? 'Not specified' }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_phone }}</dd>
					</div>
					<div class="sm:col-span-2">
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Applicant Address</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_address }}</dd>
					</div>
				</dl>
			</div>
		</div>

		<!-- Supporting Documents -->
		@if($reliefApplication->application_file)
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">Supporting Documents</h3>
				</div>
				<div class="p-6">
					<div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
						<div class="flex items-center">
							<svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
							<div>
								<p class="text-sm font-medium text-gray-900 dark:text-white">{{ $reliefApplication->file_name }}</p>
								<p class="text-xs text-gray-500 dark:text-gray-400">Application file</p>
							</div>
						</div>
						<a href="{{ $reliefApplication->file_url }}" target="_blank" class="btn-primary">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
							Download
						</a>
					</div>
				</div>
			</div>
		@endif

		<!-- Actions -->
		<div class="flex justify-end space-x-3">
			<a href="{{ route('relief-applications.index') }}" class="btn-secondary">
				Back to Applications
			</a>
			@if(auth()->user()->hasPermissionTo('relief-applications.update') || auth()->user()->hasPermissionTo('relief-applications.update-own'))
				<a href="{{ route('relief-applications.edit', $reliefApplication) }}" class="btn-primary">
					Edit Application
				</a>
			@endif
		</div>
	</div>
</x-main-layout>
