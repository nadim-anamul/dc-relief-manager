<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.relief-applications.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reliefApplication->subject }}</h1>
		</div>
	</x-slot>

	<div class="space-y-6">
		@if(!empty($hasDuplicate) && $hasDuplicate)
			<div class="p-4 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-md">
				<div class="flex items-start">
					<svg class="w-5 h-5 text-yellow-700 dark:text-yellow-300 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 4.93a10 10 0 1114.14 14.14A10 10 0 014.93 4.93z"></path>
					</svg>
					<p class="text-sm text-yellow-800 dark:text-yellow-200">
						{{ $reliefApplication->application_type === 'organization' 
							? __('Approved application already exists this year for this organization') 
							: __('Approved application already exists this year for this NID') }}
					</p>
				</div>
			</div>
		@endif
		<!-- Application Status -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Application Status') }}</h3>
					<span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $reliefApplication->status_badge_class }}">
						{{ $reliefApplication->status_display }}
					</span>
				</div>
			</div>
			<div class="p-6">
				<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
					<div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Application Date') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ bn_date($reliefApplication->date, 'd M Y') }}</dd>
					</div>
					<div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Submitted') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ bn_datetime($reliefApplication->created_at, 'd M Y, h:i A') }}</dd>
					</div>
					<div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Last Updated') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ bn_datetime($reliefApplication->updated_at, 'd M Y, h:i A') }}</dd>
					</div>
				</div>
				@if($reliefApplication->approved_at)
					<div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Approved By') }}</dt>
								<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->approvedBy->name ?? 'Unknown' }}</dd>
							</div>
							<div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Approved At') }}</dt>
								<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ bn_datetime($reliefApplication->approved_at, 'd M Y, h:i A') }}</dd>
							</div>
						</div>
					</div>
				@endif
				@if($reliefApplication->rejected_at)
					<div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Rejected By') }}</dt>
								<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->rejectedBy->name ?? 'Unknown' }}</dd>
							</div>
							<div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Rejected At') }}</dt>
								<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ bn_datetime($reliefApplication->rejected_at, 'd M Y, h:i A') }}</dd>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>

		<!-- Applicant Information -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center">
					<div class="flex-shrink-0">
						<div class="w-8 h-8 {{ $reliefApplication->application_type === 'individual' ? 'bg-green-100 dark:bg-green-900' : 'bg-blue-100 dark:bg-blue-900' }} rounded-lg flex items-center justify-center">
							@if($reliefApplication->application_type === 'individual')
								<svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
								</svg>
							@else
								<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
								</svg>
							@endif
						</div>
					</div>
					<div class="ml-3">
						<h3 class="text-lg font-medium text-gray-900 dark:text-white">
							{{ $reliefApplication->application_type === 'individual' ? __('Individual Information') : __('Organization Information') }}
						</h3>
					</div>
				</div>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					@if($reliefApplication->application_type === 'individual')
						<div>
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Applicant Name') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_name }}</dd>
						</div>
						<div>
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('NID') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_nid }}</dd>
						</div>
						<div>
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_phone }}</dd>
						</div>
						@if($reliefApplication->applicant_designation)
						<div>
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Designation') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_designation }}</dd>
						</div>
						@endif
						<div class="sm:col-span-2">
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Address') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_address }}</dd>
						</div>
					@else
						<div>
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Organization Name') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->organization_name }}</dd>
						</div>
						<div>
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Organization Type') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->organizationType->name ?? 'Not specified' }}</dd>
						</div>
						<div>
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Contact Person') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_name }}</dd>
						</div>
						<div>
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_phone }}</dd>
						</div>
						@if($reliefApplication->applicant_designation)
						<div>
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Designation') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_designation }}</dd>
						</div>
						@endif
						<div>
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('NID') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_nid }}</dd>
						</div>
						<div class="sm:col-span-2">
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Organization Address') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->organization_address }}</dd>
						</div>
						<div class="sm:col-span-2">
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Contact Person Address') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->applicant_address }}</dd>
						</div>
					@endif
				</dl>
			</div>
		</div>

		<!-- Location Information -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Location Information') }}</h3>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					<div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Zilla (District)') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->zilla?->name_display ?? __('Not specified') }}</dd>
					</div>
					<div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Upazila (Sub-district)') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->upazila?->name_display ?? __('Not specified') }}</dd>
					</div>
					<div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Union') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->union?->name_display ?? __('Not specified') }}</dd>
					</div>
					<div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Ward') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->ward?->name_display ?? __('Not specified') }}</dd>
					</div>
				</dl>
			</div>
		</div>

		<!-- Relief Information -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Relief Information') }}</h3>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					<div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Subject') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->subject }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Relief Type') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							@if($reliefApplication->reliefType)
								<div class="flex items-center">
									@if($reliefApplication->reliefType->color_code)
										<div class="w-4 h-4 rounded-full mr-2" style="background-color: {{ $reliefApplication->reliefType->color_code }}"></div>
									@endif
									{{ $reliefApplication->reliefType->display_name }}
								</div>
							@else
                                <span class="text-gray-500 dark:text-gray-400">{{ __('Not specified') }}</span>
							@endif
						</dd>
					</div>
					<div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Amount Requested') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold text-lg">{{ bn_number($reliefApplication->formatted_amount) }}</dd>
					</div>
					@if($reliefApplication->approved_amount)
						<div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Amount Approved') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white font-semibold text-lg text-green-600 dark:text-green-400">{{ bn_number($reliefApplication->formatted_approved_amount) }}</dd>
						</div>
					@endif
					@if($reliefApplication->project)
						<div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Project') }}</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefApplication->project->name }}</dd>
						</div>
					@endif
					<div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Details') }}</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							<div class="prose dark:prose-invert max-w-none">
								<p class="whitespace-pre-wrap">{{ $reliefApplication->details }}</p>
							</div>
						</dd>
					</div>
					@if($reliefApplication->general_comment)
						<div class="sm:col-span-2">
							<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('General Comment') }}@if(!app()->isLocale('bn')) (সাধারণ মন্তব্য)@endif</dt>
							<dd class="mt-1 text-sm text-gray-900 dark:text-white {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
								<div class="prose dark:prose-invert max-w-none">
									<p class="whitespace-pre-wrap">{{ $reliefApplication->general_comment }}</p>
								</div>
							</dd>
						</div>
					@endif
				</dl>
			</div>
		</div>


		<!-- Admin Remarks -->
		@if($reliefApplication->admin_remarks)
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Admin Remarks') }}</h3>
				</div>
				<div class="p-6">
					<div class="prose dark:prose-invert max-w-none">
						<p class="whitespace-pre-wrap">{{ $reliefApplication->admin_remarks }}</p>
					</div>
				</div>
			</div>
		@endif

		<!-- Supporting Documents -->
		@if($reliefApplication->application_file)
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Supporting Documents') }}</h3>
				</div>
				<div class="p-6">
					<div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
						<div class="flex items-center">
							<svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
							<div>
								<p class="text-sm font-medium text-gray-900 dark:text-white">{{ $reliefApplication->file_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Application file') }}</p>
							</div>
						</div>
                        <a href="{{ $reliefApplication->file_url }}" target="_blank" class="btn-primary">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
                            {{ __('Download') }}
						</a>
					</div>
				</div>
			</div>
		@endif

		<!-- Actions -->
		<div class="flex justify-end space-x-3">
            <a href="{{ route('admin.relief-applications.index') }}" class="btn-secondary">
                {{ __('Back to Applications') }}
            </a>
			@if(auth()->user()->hasPermissionTo('relief-applications.approve') || auth()->user()->hasPermissionTo('relief-applications.reject'))
                <a href="{{ route('admin.relief-applications.edit', $reliefApplication) }}" class="btn-primary">
                    {{ __('Review & Approve/Reject') }}
                </a>
			@endif
		</div>
	</div>
</x-main-layout>
