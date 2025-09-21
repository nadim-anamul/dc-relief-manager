<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.relief-applications.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Review Application: {{ $reliefApplication->subject }}</h1>
		</div>
	</x-slot>

	<div class="max-w-4xl mx-auto">
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Application Review & Decision</h3>
				<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Review the application details and make your decision.</p>
			</div>
			<div class="p-6">
				<form action="{{ route('admin.relief-applications.update', $reliefApplication) }}" method="POST" class="space-y-8" x-data="approvalForm()">
					@csrf
					@method('PUT')

					<!-- Application Summary -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Application Summary</h4>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Organization</label>
								<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
									<p class="text-sm text-gray-900 dark:text-white">{{ $reliefApplication->organization_name }}</p>
									@if($reliefApplication->organizationType)
										<p class="text-xs text-gray-500 dark:text-gray-400">{{ $reliefApplication->organizationType->name }}</p>
									@endif
								</div>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Relief Type</label>
								<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
									<div class="flex items-center">
										@if($reliefApplication->reliefType->color_code)
											<div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $reliefApplication->reliefType->color_code }}"></div>
										@endif
										<p class="text-sm text-gray-900 dark:text-white">{{ $reliefApplication->reliefType->name }}</p>
									</div>
								</div>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount Requested</label>
								<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
									<p class="text-sm font-medium text-gray-900 dark:text-white">{{ $reliefApplication->formatted_amount }}</p>
								</div>
							</div>
							<div>
								<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location</label>
								<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
									<p class="text-sm text-gray-900 dark:text-white">{{ $reliefApplication->zilla->name }}, {{ $reliefApplication->upazila->name }}</p>
								</div>
							</div>
						</div>
					</div>

					<!-- Decision Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Decision</h4>
						
						<div class="space-y-6">
							<!-- Status Selection -->
							<div>
								<label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Decision <span class="text-red-500">*</span>
								</label>
								<select name="status" 
									id="status" 
									x-model="status"
									@change="updateFormVisibility()"
									class="input-field @error('status') border-red-500 dark:border-red-400 @enderror"
									required>
									<option value="pending" {{ old('status', $reliefApplication->status) == 'pending' ? 'selected' : '' }}>Pending Review</option>
									<option value="approved" {{ old('status', $reliefApplication->status) == 'approved' ? 'selected' : '' }}>Approve</option>
									<option value="rejected" {{ old('status', $reliefApplication->status) == 'rejected' ? 'selected' : '' }}>Reject</option>
								</select>
								@error('status')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

					<!-- Relief Items Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Relief Items Requested</h4>
						
						<div class="space-y-4">
							@foreach($reliefApplication->reliefApplicationItems as $index => $item)
								<div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
									<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
										<!-- Item Info -->
										<div>
											<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Item</label>
											<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
												<p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->reliefItem->name }}</p>
												<p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->reliefItem->name_bn }}</p>
												<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1
													@if($item->reliefItem->type === 'monetary') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
													@elseif($item->reliefItem->type === 'food') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
													@elseif($item->reliefItem->type === 'shelter') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
													@elseif($item->reliefItem->type === 'medical') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
													@else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
													@endif">
													{{ ucfirst($item->reliefItem->type) }}
												</span>
											</div>
										</div>

										<!-- Quantity Requested -->
										<div>
											<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Requested</label>
											<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
												<p class="text-sm text-gray-900 dark:text-white">{{ number_format($item->quantity_requested, 3) }} {{ $item->reliefItem->unit }}</p>
											</div>
										</div>

										<!-- Quantity Approved -->
										<div>
											<label for="items_{{ $index }}_quantity_approved" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												Approved Quantity <span class="text-red-500">*</span>
											</label>
											<input type="number" 
												name="items[{{ $index }}][quantity_approved]" 
												id="items_{{ $index }}_quantity_approved"
												value="{{ old('items.'.$index.'.quantity_approved', $item->quantity_approved) }}"
												class="input-field @error('items.'.$index.'.quantity_approved') border-red-500 dark:border-red-400 @enderror"
												placeholder="0.000"
												min="0"
												max="{{ $item->quantity_requested }}"
												step="0.001"
												required>
											<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max: {{ number_format($item->quantity_requested, 3) }} {{ $item->reliefItem->unit }}</p>
											@error('items.'.$index.'.quantity_approved')
												<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
											@enderror
										</div>

										<!-- Unit Price (for monetary items) -->
										<div>
											<label for="items_{{ $index }}_unit_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												Unit Price (৳)
											</label>
											@if($item->reliefItem->type === 'monetary')
												<input type="number" 
													name="items[{{ $index }}][unit_price]" 
													id="items_{{ $index }}_unit_price"
													value="{{ old('items.'.$index.'.unit_price', $item->unit_price) }}"
													class="input-field @error('items.'.$index.'.unit_price') border-red-500 dark:border-red-400 @enderror"
													placeholder="0.00"
													min="0"
													step="0.01"
													required>
											@else
												<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
													<p class="text-sm text-gray-500 dark:text-gray-400">Physical Item</p>
												</div>
											@endif
											@error('items.'.$index.'.unit_price')
												<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
											@enderror
										</div>
									</div>

									<!-- Hidden fields -->
									<input type="hidden" name="items[{{ $index }}][relief_item_id]" value="{{ $item->relief_item_id }}">
									<input type="hidden" name="items[{{ $index }}][quantity_requested]" value="{{ $item->quantity_requested }}">
								</div>
							@endforeach
						</div>
					</div>

					<!-- Approval Fields (shown when status is 'approved') -->
					<div x-show="status === 'approved'" class="space-y-4">
						<div class="p-4 bg-green-50 dark:bg-green-900 rounded-lg">
							<h5 class="text-sm font-medium text-green-800 dark:text-green-200 mb-3">Approval Details</h5>
							
							<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
								<!-- Approved Amount (calculated automatically) -->
								<div>
									<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Total Approved Amount (৳)</label>
									<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
										<p class="text-sm font-medium text-gray-900 dark:text-white" id="total-approved-amount">৳0.00</p>
										<p class="text-xs text-gray-500 dark:text-gray-400">Calculated from approved quantities</p>
									</div>
								</div>

										<!-- Project Selection -->
										<div>
											<label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												Project <span class="text-red-500">*</span>
											</label>
											<select name="project_id" 
												id="project_id" 
												x-model="selectedProject"
												@change="loadProjectBudget()"
												class="input-field @error('project_id') border-red-500 dark:border-red-400 @enderror"
												required>
												<option value="">Select Project</option>
												@foreach($reliefApplication->available_projects as $project)
													<option value="{{ $project->id }}" {{ old('project_id', $reliefApplication->project_id) == $project->id ? 'selected' : '' }}>
														{{ $project->name }} ({{ $project->formatted_budget }})
													</option>
												@endforeach
											</select>
											<div x-show="selectedProject" class="mt-2 p-2 bg-blue-50 dark:bg-blue-900 rounded text-sm">
												<p class="text-blue-800 dark:text-blue-200">
													Available Budget: <span x-text="projectBudget"></span>
												</p>
											</div>
											@error('project_id')
												<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
											@enderror
										</div>
									</div>
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
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Admin Remarks</h4>
						
						<div>
							<label for="admin_remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								Remarks/Comments
							</label>
							<textarea name="admin_remarks" 
								id="admin_remarks" 
								rows="4"
								class="input-field @error('admin_remarks') border-red-500 dark:border-red-400 @enderror"
								placeholder="Enter your remarks or comments about this application">{{ old('admin_remarks', $reliefApplication->admin_remarks) }}</textarea>
							@error('admin_remarks')
								<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
							@enderror
						</div>
					</div>

					<!-- Submit Buttons -->
					<div class="flex justify-end space-x-3">
						<a href="{{ route('admin.relief-applications.show', $reliefApplication) }}" class="btn-secondary">
							Cancel
						</a>
						<button type="submit" class="btn-primary">
							<span x-show="status === 'approved'">Approve Application</span>
							<span x-show="status === 'rejected'">Reject Application</span>
							<span x-show="status === 'pending'">Update Status</span>
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
				selectedProject: '{{ old('project_id', $reliefApplication->project_id) }}',
				projectBudget: '৳0.00',
				
				updateFormVisibility() {
					// Reset project selection when changing status
					if (this.status !== 'approved') {
						this.selectedProject = '';
						this.projectBudget = '৳0.00';
					} else if (this.selectedProject) {
						this.loadProjectBudget();
					}
					this.calculateTotalApprovedAmount();
				},
				
				loadProjectBudget() {
					if (this.selectedProject) {
						fetch(`/admin/project-budget?project_id=${this.selectedProject}`)
							.then(response => response.json())
							.then(data => {
								this.projectBudget = data.formatted_budget;
							})
							.catch(error => {
								console.error('Error loading project budget:', error);
								this.projectBudget = '৳0.00';
							});
					} else {
						this.projectBudget = '৳0.00';
					}
				},
				
				calculateTotalApprovedAmount() {
					let total = 0;
					
					// Calculate from all quantity approved inputs
					document.querySelectorAll('input[name*="[quantity_approved]"]').forEach(input => {
						const quantity = parseFloat(input.value) || 0;
						const unitPriceInput = input.closest('.grid').querySelector('input[name*="[unit_price]"]');
						const unitPrice = unitPriceInput ? parseFloat(unitPriceInput.value) || 0 : 0;
						
						total += quantity * unitPrice;
					});
					
					// Update the display
					const totalElement = document.getElementById('total-approved-amount');
					if (totalElement) {
						totalElement.textContent = '৳' + total.toFixed(2);
					}
				},
				
				init() {
					if (this.selectedProject) {
						this.loadProjectBudget();
					}
					
					// Add event listeners to quantity and price inputs
					document.querySelectorAll('input[name*="[quantity_approved]"], input[name*="[unit_price]"]').forEach(input => {
						input.addEventListener('input', () => this.calculateTotalApprovedAmount());
					});
					
					this.calculateTotalApprovedAmount();
				}
			}
		}
	</script>
</x-main-layout>
