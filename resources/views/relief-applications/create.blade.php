<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('relief-applications.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Submit Relief Application</h1>
		</div>
	</x-slot>

	<div class="max-w-4xl mx-auto">
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Relief Application Form</h3>
				<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Please fill out all required fields to submit your relief application.</p>
			</div>
			<div class="p-6">
				<form action="{{ route('relief-applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="reliefApplicationForm()">
					@csrf

					<!-- Organization Information Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Organization Information</h4>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<!-- Organization Name -->
							<div class="md:col-span-2">
								<label for="organization_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Organization Name <span class="text-red-500">*</span>
								</label>
								<input type="text" 
									name="organization_name" 
									id="organization_name" 
									value="{{ old('organization_name') }}"
									class="input-field @error('organization_name') border-red-500 dark:border-red-400 @enderror"
									placeholder="Enter organization name"
									required>
								@error('organization_name')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Organization Type -->
							<div>
								<label for="organization_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Organization Type
								</label>
								<select name="organization_type_id" 
									id="organization_type_id" 
									class="input-field @error('organization_type_id') border-red-500 dark:border-red-400 @enderror">
									<option value="">Select Organization Type</option>
									@foreach($organizationTypes as $organizationType)
										<option value="{{ $organizationType->id }}" {{ old('organization_type_id') == $organizationType->id ? 'selected' : '' }}>
											{{ $organizationType->name }}
										</option>
									@endforeach
								</select>
								@error('organization_type_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Application Date -->
							<div>
								<label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Application Date <span class="text-red-500">*</span>
								</label>
								<input type="date" 
									name="date" 
									id="date" 
									value="{{ old('date', now()->format('Y-m-d')) }}"
									class="input-field @error('date') border-red-500 dark:border-red-400 @enderror"
									required>
								@error('date')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Organization Address -->
							<div class="md:col-span-2">
								<label for="organization_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Organization Address <span class="text-red-500">*</span>
								</label>
								<textarea name="organization_address" 
									id="organization_address" 
									rows="3"
									class="input-field @error('organization_address') border-red-500 dark:border-red-400 @enderror"
									placeholder="Enter organization address"
									required>{{ old('organization_address') }}</textarea>
								@error('organization_address')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
						</div>
					</div>

					<!-- Location Information Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Location Information</h4>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<!-- Zilla Selection -->
							<div>
								<label for="zilla_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Zilla (District) <span class="text-red-500">*</span>
								</label>
								<select name="zilla_id" 
									id="zilla_id" 
									x-model="selectedZilla"
									@change="loadUpazilas()"
									class="input-field @error('zilla_id') border-red-500 dark:border-red-400 @enderror"
									required>
									<option value="">Select a Zilla</option>
									@foreach($zillas as $zilla)
										<option value="{{ $zilla->id }}" {{ old('zilla_id') == $zilla->id ? 'selected' : '' }}>
											{{ $zilla->name }} ({{ $zilla->name_bn }})
										</option>
									@endforeach
								</select>
								@error('zilla_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Upazila Selection -->
							<div>
								<label for="upazila_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Upazila (Sub-district) <span class="text-red-500">*</span>
								</label>
								<select name="upazila_id" 
									id="upazila_id" 
									x-model="selectedUpazila"
									@change="loadUnions()"
									class="input-field @error('upazila_id') border-red-500 dark:border-red-400 @enderror"
									:disabled="!selectedZilla"
									required>
									<option value="">Select a Upazila</option>
									<template x-for="upazila in upazilas" :key="upazila.id">
										<option :value="upazila.id" x-text="upazila.name + ' (' + upazila.name_bn + ')'"></option>
									</template>
								</select>
								@error('upazila_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Union Selection -->
							<div>
								<label for="union_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Union <span class="text-red-500">*</span>
								</label>
								<select name="union_id" 
									id="union_id" 
									x-model="selectedUnion"
									@change="loadWards()"
									class="input-field @error('union_id') border-red-500 dark:border-red-400 @enderror"
									:disabled="!selectedUpazila"
									required>
									<option value="">Select a Union</option>
									<template x-for="union in unions" :key="union.id">
										<option :value="union.id" x-text="union.name + ' (' + union.name_bn + ')'"></option>
									</template>
								</select>
								@error('union_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Ward Selection -->
							<div>
								<label for="ward_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Ward <span class="text-red-500">*</span>
								</label>
								<select name="ward_id" 
									id="ward_id" 
									x-model="selectedWard"
									class="input-field @error('ward_id') border-red-500 dark:border-red-400 @enderror"
									:disabled="!selectedUnion"
									required>
									<option value="">Select a Ward</option>
									<template x-for="ward in wards" :key="ward.id">
										<option :value="ward.id" x-text="ward.name + ' (' + ward.name_bn + ')'"></option>
									</template>
								</select>
								@error('ward_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
						</div>
					</div>

					<!-- Relief Information Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Relief Information</h4>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<!-- Subject -->
							<div class="md:col-span-2">
								<label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Subject <span class="text-red-500">*</span>
								</label>
								<input type="text" 
									name="subject" 
									id="subject" 
									value="{{ old('subject') }}"
									class="input-field @error('subject') border-red-500 dark:border-red-400 @enderror"
									placeholder="Enter application subject"
									required>
								@error('subject')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Relief Type -->
							<div>
								<label for="relief_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Relief Type <span class="text-red-500">*</span>
								</label>
								<select name="relief_type_id" 
									id="relief_type_id" 
									class="input-field @error('relief_type_id') border-red-500 dark:border-red-400 @enderror"
									required>
									<option value="">Select Relief Type</option>
									@foreach($reliefTypes as $reliefType)
										<option value="{{ $reliefType->id }}" {{ old('relief_type_id') == $reliefType->id ? 'selected' : '' }}>
											{{ $reliefType->name }} ({{ $reliefType->name_bn }})
										</option>
									@endforeach
								</select>
								@error('relief_type_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Details -->
							<div class="md:col-span-2">
								<label for="details" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Application Details <span class="text-red-500">*</span>
								</label>
								<textarea name="details" 
									id="details" 
									rows="5"
									class="input-field @error('details') border-red-500 dark:border-red-400 @enderror"
									placeholder="Provide detailed information about your relief request"
									required>{{ old('details') }}</textarea>
								@error('details')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
						</div>
					</div>

					<!-- Relief Items Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<div class="flex items-center justify-between mb-6">
							<h4 class="text-lg font-medium text-gray-900 dark:text-white">Relief Items Requested</h4>
							<button type="button" @click="addReliefItem()" class="btn-secondary">
								<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
								</svg>
								Add Relief Item
							</button>
						</div>

						<div x-data="{ reliefItems: [] }" class="space-y-4">
							<!-- Dynamic Relief Items -->
							<template x-for="(item, index) in reliefItems" :key="index">
								<div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4">
									<div class="flex items-center justify-between mb-4">
										<h5 class="text-md font-medium text-gray-900 dark:text-white">Relief Item <span x-text="index + 1"></span></h5>
										<button type="button" @click="removeReliefItem(index)" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
											<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
											</svg>
										</button>
									</div>
									
									<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
										<!-- Relief Item Selection -->
										<div>
											<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												Relief Item <span class="text-red-500">*</span>
											</label>
											<select :name="`relief_items[${index}][relief_item_id]`" 
												x-model="item.relief_item_id"
												@change="updateItemDetails(index)"
												class="input-field"
												required>
												<option value="">Select Relief Item</option>
												@foreach($reliefItems as $reliefItem)
													<option value="{{ $reliefItem->id }}" 
														data-type="{{ $reliefItem->type }}"
														data-unit="{{ $reliefItem->unit }}"
														data-name="{{ $reliefItem->name }}">
														{{ $reliefItem->name }} ({{ $reliefItem->name_bn }}) - {{ $reliefItem->formatted_unit }}
													</option>
												@endforeach
											</select>
										</div>

										<!-- Quantity Requested -->
										<div>
											<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												Quantity Requested <span class="text-red-500">*</span>
											</label>
											<input type="number" 
												:name="`relief_items[${index}][quantity_requested]`"
												x-model="item.quantity_requested"
												class="input-field"
												placeholder="0.000"
												min="0"
												step="0.001"
												required>
											<p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-text="item.unit ? 'Unit: ' + item.unit : ''"></p>
										</div>

										<!-- Unit Price (for monetary items only) -->
										<div x-show="item.type === 'monetary'">
											<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												Unit Price (৳) <span class="text-red-500">*</span>
											</label>
											<input type="number" 
												:name="`relief_items[${index}][unit_price]`"
												x-model="item.unit_price"
												class="input-field"
												placeholder="0.00"
												min="0"
												step="0.01"
												required>
											<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required for cash relief only</p>
										</div>
										
										<!-- Physical Item Info -->
										<div x-show="item.type !== 'monetary'" class="md:col-span-1">
											<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												Item Type
											</label>
											<div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
												<p class="text-sm text-gray-600 dark:text-gray-400">
													<span x-text="item.type ? item.type.charAt(0).toUpperCase() + item.type.slice(1) : 'Physical Item'"></span>
												</p>
												<p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
													Physical items are provided directly, no monetary value needed
												</p>
											</div>
										</div>

										<!-- Remarks -->
										<div class="md:col-span-3">
											<label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												Remarks
											</label>
											<input type="text" 
												:name="`relief_items[${index}][remarks]`"
												x-model="item.remarks"
												class="input-field"
												placeholder="Additional notes for this item">
										</div>
									</div>
								</div>
							</template>

							<!-- No Items Message -->
							<div x-show="reliefItems.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
								<svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
								</svg>
								<p>No relief items added yet. Click "Add Relief Item" to get started.</p>
							</div>
						</div>
					</div>

					<!-- Applicant Information Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Applicant Information</h4>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<!-- Applicant Name -->
							<div>
								<label for="applicant_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Applicant Name <span class="text-red-500">*</span>
								</label>
								<input type="text" 
									name="applicant_name" 
									id="applicant_name" 
									value="{{ old('applicant_name') }}"
									class="input-field @error('applicant_name') border-red-500 dark:border-red-400 @enderror"
									placeholder="Enter applicant name"
									required>
								@error('applicant_name')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Applicant Designation -->
							<div>
								<label for="applicant_designation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Designation
								</label>
								<input type="text" 
									name="applicant_designation" 
									id="applicant_designation" 
									value="{{ old('applicant_designation') }}"
									class="input-field @error('applicant_designation') border-red-500 dark:border-red-400 @enderror"
									placeholder="Enter designation">
								@error('applicant_designation')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Applicant Phone -->
							<div>
								<label for="applicant_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Phone Number <span class="text-red-500">*</span>
								</label>
								<input type="tel" 
									name="applicant_phone" 
									id="applicant_phone" 
									value="{{ old('applicant_phone') }}"
									class="input-field @error('applicant_phone') border-red-500 dark:border-red-400 @enderror"
									placeholder="Enter phone number"
									required>
								@error('applicant_phone')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Applicant Address -->
							<div class="md:col-span-2">
								<label for="applicant_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
									Applicant Address <span class="text-red-500">*</span>
								</label>
								<textarea name="applicant_address" 
									id="applicant_address" 
									rows="3"
									class="input-field @error('applicant_address') border-red-500 dark:border-red-400 @enderror"
									placeholder="Enter applicant address"
									required>{{ old('applicant_address') }}</textarea>
								@error('applicant_address')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
						</div>
					</div>

					<!-- File Upload Section -->
					<div class="pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Supporting Documents</h4>
						
						<div>
							<label for="application_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								Application File
							</label>
							<div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
								<div class="space-y-1 text-center">
									<svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
										<path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
									<div class="flex text-sm text-gray-600 dark:text-gray-400">
										<label for="application_file" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
											<span>Upload a file</span>
											<input id="application_file" 
												name="application_file" 
												type="file" 
												class="sr-only"
												accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
												@change="handleFileChange($event)">
										</label>
										<p class="pl-1">or drag and drop</p>
									</div>
									<p class="text-xs text-gray-500 dark:text-gray-400">
										PDF, DOC, DOCX, JPG, PNG up to 10MB
									</p>
								</div>
							</div>
							<div x-show="selectedFile" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
								<span x-text="selectedFile"></span>
							</div>
							@error('application_file')
								<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
							@enderror
						</div>
					</div>

					<!-- Submit Buttons -->
					<div class="flex justify-end space-x-3">
						<a href="{{ route('relief-applications.index') }}" class="btn-secondary">
							Cancel
						</a>
						<button type="submit" class="btn-primary">
							Submit Application
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		function reliefApplicationForm() {
			return {
				selectedZilla: '{{ old('zilla_id') }}',
				selectedUpazila: '{{ old('upazila_id') }}',
				selectedUnion: '{{ old('union_id') }}',
				selectedWard: '{{ old('ward_id') }}',
				upazilas: [],
				unions: [],
				wards: [],
				selectedFile: null,
				reliefItems: [],
				
				loadUpazilas() {
					if (this.selectedZilla) {
						fetch(`/upazilas-by-zilla/${this.selectedZilla}`)
							.then(response => response.json())
							.then(data => {
								this.upazilas = data;
								this.selectedUpazila = '';
								this.selectedUnion = '';
								this.selectedWard = '';
								this.unions = [];
								this.wards = [];
							})
							.catch(error => {
								console.error('Error loading upazilas:', error);
							});
					} else {
						this.upazilas = [];
						this.selectedUpazila = '';
						this.selectedUnion = '';
						this.selectedWard = '';
						this.unions = [];
						this.wards = [];
					}
				},
				
				loadUnions() {
					if (this.selectedUpazila) {
						fetch(`/unions-by-upazila/${this.selectedUpazila}`)
							.then(response => response.json())
							.then(data => {
								this.unions = data;
								this.selectedUnion = '';
								this.selectedWard = '';
								this.wards = [];
							})
							.catch(error => {
								console.error('Error loading unions:', error);
							});
					} else {
						this.unions = [];
						this.selectedUnion = '';
						this.selectedWard = '';
						this.wards = [];
					}
				},
				
				loadWards() {
					if (this.selectedUnion) {
						fetch(`/wards-by-union/${this.selectedUnion}`)
							.then(response => response.json())
							.then(data => {
								this.wards = data;
								this.selectedWard = '';
							})
							.catch(error => {
								console.error('Error loading wards:', error);
							});
					} else {
						this.wards = [];
						this.selectedWard = '';
					}
				},
				
				handleFileChange(event) {
					const file = event.target.files[0];
					if (file) {
						this.selectedFile = file.name;
					} else {
						this.selectedFile = null;
					}
				},
				
				addReliefItem() {
					this.reliefItems.push({
						relief_item_id: '',
						quantity_requested: '',
						unit_price: '',
						remarks: '',
						type: '',
						unit: ''
					});
				},
				
				removeReliefItem(index) {
					this.reliefItems.splice(index, 1);
				},
				
				updateItemDetails(index) {
					const selectElement = document.querySelector(`select[name="relief_items[${index}][relief_item_id]"]`);
					const selectedOption = selectElement.options[selectElement.selectedIndex];
					
					if (selectedOption.value) {
						this.reliefItems[index].type = selectedOption.dataset.type;
						this.reliefItems[index].unit = selectedOption.dataset.unit;
					} else {
						this.reliefItems[index].type = '';
						this.reliefItems[index].unit = '';
					}
				},
				
				init() {
					if (this.selectedZilla) {
						this.loadUpazilas();
					}
					if (this.selectedUpazila) {
						this.loadUnions();
					}
					if (this.selectedUnion) {
						this.loadWards();
					}
					
					// Add at least one relief item by default
					if (this.reliefItems.length === 0) {
						this.addReliefItem();
					}
				}
			}
		}
	</script>
</x-main-layout>
