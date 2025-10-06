<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.relief-applications.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Review Application') }}: {{ $reliefApplication->subject }}</h1>
		</div>
	</x-slot>

	<div class="max-w-4xl mx-auto">
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Application Review & Decision') }}</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('Review the application details and make your decision.') }}</p>
			</div>
			<div class="p-6">
				<form action="{{ route('admin.relief-applications.update', $reliefApplication) }}" method="POST" class="space-y-8" x-data="approvalForm()">
					@csrf
					@method('PUT')

					<!-- Application Summary -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">{{ __('Application Summary') }}</h4>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Organization') }}</label>
								<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
									<p class="text-sm text-gray-900 dark:text-white">{{ $reliefApplication->organization_name }}</p>
									@if($reliefApplication->organizationType)
										<p class="text-xs text-gray-500 dark:text-gray-400">{{ $reliefApplication->organizationType->name }}</p>
									@endif
								</div>
							</div>
							<div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Relief Type') }}</label>
								<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
									@if($reliefApplication->reliefType)
										<div class="flex items-center">
											@if($reliefApplication->reliefType->color_code)
												<div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $reliefApplication->reliefType->color_code }}"></div>
											@endif
											<p class="text-sm text-gray-900 dark:text-white">{{ $reliefApplication->reliefType->name }}</p>
										</div>
									@else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Not specified') }}</p>
									@endif
								</div>
							</div>
							<div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Amount Requested') }}</label>
								<div class="p-3 bg-blue-50 dark:bg-blue-900 rounded-lg border border-blue-200 dark:border-blue-700">
									<p class="text-lg font-semibold text-blue-900 dark:text-blue-100">{{ $reliefApplication->formatted_amount }}</p>
                                    <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">{{ __('Total amount requested by applicant') }}</p>
								</div>
							</div>
							<div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Location') }}</label>
								<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $reliefApplication->zilla?->name ?? __('Not specified') }}, {{ $reliefApplication->upazila?->name ?? __('Not specified') }}</p>
								</div>
							</div>
							<div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Project') }}</label>
								<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
									@if($reliefApplication->project)
										<p class="text-sm font-medium text-gray-900 dark:text-white">{{ $reliefApplication->project->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ __('Available') }}: {{ $reliefApplication->project->formatted_available_amount }}
                                        </p>
									@else
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No project assigned') }}</p>
									@endif
								</div>
							</div>
						</div>
					</div>

					<!-- Decision Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<div class="flex items-center justify-between mb-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Review Decision') }}</h4>
							<div class="flex items-center space-x-2">
								<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reliefApplication->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : ($reliefApplication->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
									{{ ucfirst($reliefApplication->status) }}
								</span>
							</div>
						</div>
						
						<div class="space-y-6">
							<!-- Status Selection -->
							<div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
								<label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
									Select Decision <span class="text-red-500">*</span>
								</label>
								<div class="grid grid-cols-1 md:grid-cols-3 gap-3">
									<label class="relative cursor-pointer">
										<input type="radio" 
											name="status" 
											value="pending" 
											x-model="status"
											@change="updateFormVisibility()"
											class="sr-only">
										<div class="p-3 border-2 rounded-lg transition-all duration-200" 
											:class="status === 'pending' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
											<div class="flex items-center">
												<div class="w-4 h-4 rounded-full border-2 mr-3" 
													:class="status === 'pending' ? 'border-blue-500 bg-blue-500' : 'border-gray-300 dark:border-gray-500'">
													<div x-show="status === 'pending'" class="w-2 h-2 bg-white rounded-full m-0.5"></div>
												</div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Pending Review') }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Keep under review') }}</p>
                                                </div>
											</div>
										</div>
									</label>
									
									<label class="relative cursor-pointer">
										<input type="radio" 
											name="status" 
											value="approved" 
											x-model="status"
											@change="updateFormVisibility()"
											class="sr-only">
										<div class="p-3 border-2 rounded-lg transition-all duration-200" 
											:class="status === 'approved' ? 'border-green-500 bg-green-50 dark:bg-green-900' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
											<div class="flex items-center">
												<div class="w-4 h-4 rounded-full border-2 mr-3" 
													:class="status === 'approved' ? 'border-green-500 bg-green-500' : 'border-gray-300 dark:border-gray-500'">
													<div x-show="status === 'approved'" class="w-2 h-2 bg-white rounded-full m-0.5"></div>
												</div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Approve') }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Grant relief') }}</p>
                                                </div>
											</div>
										</div>
									</label>
									
									<label class="relative cursor-pointer">
										<input type="radio" 
											name="status" 
											value="rejected" 
											x-model="status"
											@change="updateFormVisibility()"
											class="sr-only">
										<div class="p-3 border-2 rounded-lg transition-all duration-200" 
											:class="status === 'rejected' ? 'border-red-500 bg-red-50 dark:bg-red-900' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500'">
											<div class="flex items-center">
												<div class="w-4 h-4 rounded-full border-2 mr-3" 
													:class="status === 'rejected' ? 'border-red-500 bg-red-500' : 'border-gray-300 dark:border-gray-500'">
													<div x-show="status === 'rejected'" class="w-2 h-2 bg-white rounded-full m-0.5"></div>
												</div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Reject') }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Deny application') }}</p>
                                                </div>
											</div>
										</div>
									</label>
								</div>
								@error('status')
									<p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>


					<!-- Approval Fields (shown when status is 'approved') -->
					<div x-show="status === 'approved'" class="space-y-6">
						<div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-6">
							<div class="flex items-center mb-4">
								<div class="flex-shrink-0">
									<svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
									</svg>
								</div>
                                <h5 class="ml-3 text-lg font-medium text-green-800 dark:text-green-200">{{ __('Approval Details') }}</h5>
							</div>
							
							<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
								<!-- Approval Amount Input -->
								<div>
                                    <label for="approved_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        {{ __('Final Approval Amount') }} <span class="text-red-500">*</span>
                                    </label>
									<div class="relative">
										<input type="number" 
											name="approved_amount" 
											id="approved_amount"
											value="{{ old('approved_amount', $reliefApplication->approved_amount) }}"
											class="input-field @error('approved_amount') border-red-500 dark:border-red-400 @enderror"
                                            placeholder="0.00"
											min="0"
											max="{{ $reliefApplication->project ? $reliefApplication->project->available_amount : 0 }}"
											step="0.01"
											@input="validateApprovalAmount()"
											required>
										<div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
											<span class="text-gray-500 dark:text-gray-400 text-sm">
												@if($reliefApplication->project && $reliefApplication->project->reliefType)
													@php
														$unit = $reliefApplication->project->reliefType->unit_bn ?? $reliefApplication->project->reliefType->unit ?? '';
													@endphp
													@if(in_array($unit, ['টাকা', 'Taka']))
														৳
													@else
														{{ $unit }}
													@endif
												@else
													৳
												@endif
											</span>
										</div>
									</div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        {{ __('Enter the final amount to be approved for this application') }}
                                    </p>
									@error('approved_amount')
										<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
									@enderror
									<div x-show="insufficientFunds" class="mt-2 p-2 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded text-sm">
                                        <p class="text-red-800 dark:text-red-200">
                                            <span class="font-medium">{{ __('Insufficient funds!') }}</span> {{ __('Approval amount exceeds available project budget.') }}
                                        </p>
									</div>
								</div>

								<!-- Available Project Budget -->
								<div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Available Project Budget') }}</label>
									@if($reliefApplication->project)
										<div class="p-3 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg">
											<div class="flex items-center mb-2">
												<svg class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
												</svg>
												<div>
                                                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ __('Project') }}: {{ $reliefApplication->project->name }}</p>
													<p class="text-lg font-semibold text-blue-900 dark:text-blue-100">
														{{ $reliefApplication->project->formatted_available_amount }}
													</p>
												</div>
											</div>
                                            <p class="text-xs text-blue-700 dark:text-blue-300">
                                                {{ __('Maximum amount that can be approved') }}
                                            </p>
										</div>
									@else
										<div class="p-3 bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 rounded-lg">
											<div class="flex items-center">
												<svg class="h-5 w-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
												</svg>
												<div>
                                                    <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ __('No Project Assigned') }}</p>
                                                    <p class="text-xs text-red-700 dark:text-red-300">{{ __('Cannot approve without a project') }}</p>
												</div>
											</div>
										</div>
									@endif
								</div>
							</div>
							
							<!-- Hidden project_id field -->
							@if($reliefApplication->project_id)
								<input type="hidden" name="project_id" value="{{ $reliefApplication->project_id }}">
							@endif
						</div>
					</div>

							<!-- Rejection Fields (shown when status is 'rejected') -->
							<div x-show="status === 'rejected'" class="p-4 bg-red-50 dark:bg-red-900 rounded-lg">
								<h5 class="text-sm font-medium text-red-800 dark:text-red-200 mb-3">Rejection Details</h5>
								<p class="text-sm text-red-700 dark:text-red-300">Please provide a reason for rejection in the admin remarks section below.</p>
							</div>
						</div>
					</div>

					<!-- Admin Remarks -->
					<div class="pb-8">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">{{ __('Admin Remarks') }}</h4>
						
						<div>
							<label for="admin_remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Remarks/Comments') }}
							</label>
							<textarea name="admin_remarks" 
								id="admin_remarks" 
								rows="4"
								class="input-field @error('admin_remarks') border-red-500 dark:border-red-400 @enderror"
                                placeholder="{{ __('Enter your remarks or comments about this application') }}">{{ old('admin_remarks', $reliefApplication->admin_remarks) }}</textarea>
							@error('admin_remarks')
								<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
							@enderror
						</div>
					</div>

					<!-- Submit Buttons -->
					<div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.relief-applications.show', $reliefApplication) }}" class="btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            <span x-show="status === 'approved'">{{ __('Approve Application') }}</span>
                            <span x-show="status === 'rejected'">{{ __('Reject Application') }}</span>
                            <span x-show="status === 'pending'">{{ __('Update Status') }}</span>
                        </button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		function approvalForm() {
			return {
				status: '{{ old('status', $reliefApplication->status) }}',
				insufficientFunds: false,
				availableBudget: {{ $reliefApplication->project ? $reliefApplication->project->available_amount : 0 }},
				
				updateFormVisibility() {
					// Reset validation when changing status
					if (this.status !== 'approved') {
						this.insufficientFunds = false;
					} else {
						this.validateApprovalAmount();
					}
				},
				
				validateApprovalAmount() {
					const approvalAmountInput = document.getElementById('approved_amount');
					if (approvalAmountInput && this.availableBudget > 0) {
						const approvalAmount = parseFloat(approvalAmountInput.value) || 0;
						this.insufficientFunds = approvalAmount > this.availableBudget;
					} else {
						this.insufficientFunds = false;
					}
				},
				
				init() {
					// Add event listener to approval amount input
					const approvalAmountInput = document.getElementById('approved_amount');
					if (approvalAmountInput) {
						approvalAmountInput.addEventListener('input', () => this.validateApprovalAmount());
					}
					
					// Initial validation
					this.validateApprovalAmount();
				}
			}
		}
	</script>
</x-main-layout>
