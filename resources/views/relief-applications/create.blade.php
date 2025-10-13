<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('relief-applications.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Submit Relief Application') }}</h1>
		</div>
	</x-slot>

	<div class="max-w-5xl mx-auto">
		<!-- Main Form Card -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 px-8 py-6 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center">
					<div class="flex-shrink-0">
						<div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
						</div>
					</div>
					<div class="ml-4">
							<h3 class="text-xl font-bold text-gray-900 dark:text-white {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Relief Application Form') }}</h3>
							<p class="text-sm text-gray-600 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Please complete all sections to submit your relief request') }}</p>
					</div>
				</div>
			</div>
			<div class="p-8">
				<form action="{{ route('relief-applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="reliefApplicationForm()">
					@csrf

					<!-- Organization Information Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<div class="flex items-center mb-6">
							<div class="flex-shrink-0">
								<div class="w-8 h-8 bg-gradient-to-r from-green-400 to-green-600 rounded-lg flex items-center justify-center">
									<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
									</svg>
								</div>
							</div>
							<div class="ml-3">
									<h4 class="text-lg font-semibold text-gray-900 dark:text-white {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Organization Information') }}</h4>
									<p class="text-sm text-gray-500 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Tell us about your organization') }}</p>
							</div>
						</div>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<!-- Organization Name -->
							<div class="md:col-span-2">
											<label for="organization_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												{{ __('Organization Name') }} <span class="text-red-500">*</span>
											</label>
								<input type="text" 
									name="organization_name" 
									id="organization_name" 
									value="{{ old('organization_name') }}"
									class="input-field @error('organization_name') border-red-500 dark:border-red-400 @enderror"
												placeholder="{{ __('Enter organization name') }}"
									required>
								@error('organization_name')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Organization Type -->
							<div>
										<label for="organization_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
											{{ __('Organization Type') }}
										</label>
								<select name="organization_type_id" 
									id="organization_type_id" 
									class="input-field @error('organization_type_id') border-red-500 dark:border-red-400 @enderror">
											<option value="">{{ __('Select Organization Type') }}</option>
									@foreach($organizationTypes as $organizationType)
										<option value="{{ $organizationType->id }}" {{ old('organization_type_id') == $organizationType->id ? 'selected' : '' }}>
													{{ $organizationType->name_bn ?? $organizationType->name }}
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
											{{ __('Application Date') }} <span class="text-red-500">*</span>
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
												{{ __('Organization Address') }} <span class="text-red-500">*</span>
											</label>
								<textarea name="organization_address" 
									id="organization_address" 
									rows="3"
									class="input-field @error('organization_address') border-red-500 dark:border-red-400 @enderror"
												placeholder="{{ __('Enter organization address') }}"
									required>{{ old('organization_address') }}</textarea>
								@error('organization_address')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
						</div>
					</div>

					<!-- Location Information Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<div class="flex items-center mb-6">
							<div class="flex-shrink-0">
								<div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
									<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
									</svg>
								</div>
							</div>
							<div class="ml-3">
									<h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Location Information') }}</h4>
									<p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Specify your geographic location') }}</p>
							</div>
						</div>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<!-- Zilla Selection -->
							<div>
										<label for="zilla_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
											{{ __('Zilla (District)') }} <span class="text-red-500">*</span>
										</label>
								<select name="zilla_id" 
									id="zilla_id" 
									x-model="selectedZilla"
									@change="loadUpazilas()"
									class="input-field @error('zilla_id') border-red-500 dark:border-red-400 @enderror"
									required>
											<option value="">{{ __('Select a Zilla') }}</option>
									@foreach($zillas as $zilla)
										<option value="{{ $zilla->id }}" {{ old('zilla_id') == $zilla->id ? 'selected' : '' }}>
													{{ $zilla->name_bn }}
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
											{{ __('Upazila (Sub-district)') }} <span class="text-red-500">*</span>
										</label>
								<select name="upazila_id" 
									id="upazila_id" 
									x-model="selectedUpazila"
									@change="loadUnions()"
									class="input-field @error('upazila_id') border-red-500 dark:border-red-400 @enderror"
									:disabled="!selectedZilla"
									required>
											<option value="">{{ __('Select a Upazila') }}</option>
									<template x-for="upazila in upazilas" :key="upazila.id">
										<option :value="upazila.id" x-text="upazila.name_bn || upazila.name"></option>
									</template>
								</select>
								@error('upazila_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Union Selection -->
							<div>
										<label for="union_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
											{{ __('Union') }} <span class="text-red-500">*</span>
										</label>
								<select name="union_id" 
									id="union_id" 
									x-model="selectedUnion"
									@change="loadWards()"
									class="input-field @error('union_id') border-red-500 dark:border-red-400 @enderror"
									:disabled="!selectedUpazila"
									required>
											<option value="">{{ __('Select a Union') }}</option>
									<template x-for="union in unions" :key="union.id">
										<option :value="union.id" x-text="union.name_bn || union.name"></option>
									</template>
								</select>
								@error('union_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Ward Selection -->
							<div>
										<label for="ward_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
											{{ __('Ward') }}
										</label>
								<select name="ward_id" 
									id="ward_id" 
									x-model="selectedWard"
									class="input-field @error('ward_id') border-red-500 dark:border-red-400 @enderror"
									:disabled="!selectedUnion">
											<option value="">{{ __('Select a Ward') }}</option>
									<template x-for="ward in wards" :key="ward.id">
										<option :value="ward.id" x-text="ward.name_bn || ward.name"></option>
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
						<div class="flex items-center mb-6">
							<div class="flex-shrink-0">
								<div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
									<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
									</svg>
								</div>
							</div>
							<div class="ml-3">
									<h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Relief Information') }}</h4>
									<p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Details about your relief request') }}</p>
							</div>
						</div>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<!-- Project Selection -->
							<div class="md:col-span-2">
											<label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												{{ __('Project') }} <span class="text-red-500">*</span>
											</label>
								<select name="project_id" 
									id="project_id" 
									class="input-field @error('project_id') border-red-500 dark:border-red-400 @enderror"
									required
									x-model="selectedProject"
									@change="updateProjectDetails()">
												<option value="">{{ __('Select Project') }}</option>
									<template x-for="project in projects" :key="project.id">
										<option :value="project.id" 
											:data-unit="project.relief_type_unit_bn || project.relief_type_unit"
											:data-relief-type="project.relief_type_name_bn || project.relief_type_name"
											x-text="project.name_bn || project.name"></option>
									</template>
								</select>
											<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
												{{ __('Only current economic year active projects are shown') }}
											</p>
								@error('project_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Amount Requested -->
							<div>
										<label for="amount_requested" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
											{{ __('Amount Requested') }} <span class="text-red-500">*</span>
										</label>
								<div class="relative">
									<input type="number" 
										name="amount_requested" 
										id="amount_requested" 
										value="{{ old('amount_requested') }}"
										class="input-field @error('amount_requested') border-red-500 dark:border-red-400 @enderror pr-20"
													placeholder="{{ __('Enter requested amount') }}"
										min="0.01"
										step="0.01"
										required>
									<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
															<span class="text-gray-500 dark:text-gray-400 text-sm" x-text="projectUnit || '{{ __('Select project first') }}'"></span>
									</div>
								</div>
											<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
												{{ __('Amount will be displayed with appropriate unit based on selected project\'s relief type') }}
											</p>
								@error('amount_requested')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Subject -->
							<div>
										<label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
											{{ __('Subject') }} <span class="text-red-500">*</span>
										</label>
								<input type="text" 
									name="subject" 
									id="subject" 
									value="{{ old('subject') }}"
									class="input-field @error('subject') border-red-500 dark:border-red-400 @enderror"
												placeholder="{{ __('Brief description of your relief request') }}"
									required>
								@error('subject')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Details -->
							<div class="md:col-span-2">
											<label for="details" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												{{ __('Application Details') }} <span class="text-red-500">*</span>
											</label>
								<textarea name="details" 
									id="details" 
									rows="5"
									class="input-field @error('details') border-red-500 dark:border-red-400 @enderror"
												placeholder="{{ __('Provide detailed information about your relief request') }}"
									required>{{ old('details') }}</textarea>
								@error('details')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
						</div>
					</div>

					<!-- Applicant Information Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<div class="flex items-center mb-6">
							<div class="flex-shrink-0">
								<div class="w-8 h-8 bg-gradient-to-r from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
									<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
									</svg>
								</div>
							</div>
							<div class="ml-3">
									<h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Applicant Information') }}</h4>
									<p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Contact person details') }}</p>
							</div>
						</div>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<!-- Applicant Name -->
							<div>
										<label for="applicant_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
											{{ __('Applicant Name') }} <span class="text-red-500">*</span>
										</label>
								<input type="text" 
									name="applicant_name" 
									id="applicant_name" 
									value="{{ old('applicant_name') }}"
									class="input-field @error('applicant_name') border-red-500 dark:border-red-400 @enderror"
												placeholder="{{ __('Enter applicant name') }}"
									required>
								@error('applicant_name')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Applicant Designation -->
							<div>
										<label for="applicant_designation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
											{{ __('Designation') }}
										</label>
								<input type="text" 
									name="applicant_designation" 
									id="applicant_designation" 
									value="{{ old('applicant_designation') }}"
									class="input-field @error('applicant_designation') border-red-500 dark:border-red-400 @enderror"
												placeholder="{{ __('Enter designation') }}">
								@error('applicant_designation')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Applicant Phone -->
							<div>
										<label for="applicant_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
											{{ __('Phone Number') }} <span class="text-red-500">*</span>
										</label>
								<input type="tel" 
									name="applicant_phone" 
									id="applicant_phone" 
									value="{{ old('applicant_phone') }}"
									class="input-field @error('applicant_phone') border-red-500 dark:border-red-400 @enderror"
												placeholder="{{ __('Enter phone number') }}"
									required>
								@error('applicant_phone')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Applicant Address -->
							<div class="md:col-span-2">
											<label for="applicant_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
												{{ __('Applicant Address') }} <span class="text-red-500">*</span>
											</label>
								<textarea name="applicant_address" 
									id="applicant_address" 
									rows="3"
									class="input-field @error('applicant_address') border-red-500 dark:border-red-400 @enderror"
												placeholder="{{ __('Enter applicant address') }}"
									required>{{ old('applicant_address') }}</textarea>
								@error('applicant_address')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
						</div>
					</div>

					<!-- File Upload Section -->
					<div class="pb-8">
						<div class="flex items-center mb-6">
							<div class="flex-shrink-0">
								<div class="w-8 h-8 bg-gradient-to-r from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center">
									<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
									</svg>
								</div>
							</div>
							<div class="ml-3">
									<h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Supporting Documents') }}</h4>
									<p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Attach supporting documents (optional)') }}</p>
							</div>
						</div>
						
						<div>
										<label for="application_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
											{{ __('Application File') }}
										</label>
							<div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
								<div class="space-y-1 text-center">
									<svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
										<path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
									<div class="flex text-sm text-gray-600 dark:text-gray-400">
										<label for="application_file" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
															<span>{{ __('Upload a file') }}</span>
											<input id="application_file" 
												name="application_file" 
												type="file" 
												class="sr-only"
												accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
												@change="handleFileChange($event)">
										</label>
														<p class="pl-1">{{ __('or drag and drop') }}</p>
									</div>
														<p class="text-xs text-gray-500 dark:text-gray-400">
															{{ __('PDF, DOC, DOCX, JPG, PNG up to 10MB') }}
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

					<!-- Submit Section -->
					<div class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
						<div class="flex items-center justify-between">
							<div>
											<h5 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Ready to Submit?') }}</h5>
											<p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Please review all information before submitting your application.') }}</p>
							</div>
							<div class="flex space-x-3">
												<a href="{{ route('relief-applications.index') }}" class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
													{{ __('Cancel') }}
												</a>
								<button type="submit" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center space-x-2">
									<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
									</svg>
													<span>{{ __('Submit Application') }}</span>
								</button>
							</div>
						</div>
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
				selectedProject: '{{ old('project_id') }}',
				projectUnit: '',
				selectedReliefType: '',
				projects: @json($projects ?? []),
				
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
				
				updateProjectDetails() {
					const selectElement = document.getElementById('project_id');
					const selectedOption = selectElement.options[selectElement.selectedIndex];
					
					if (selectedOption.value) {
						this.projectUnit = selectedOption.dataset.unit;
						this.selectedReliefType = selectedOption.dataset.reliefType;
					} else {
						this.projectUnit = '';
						this.selectedReliefType = '';
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
					
					// Initialize project details if already selected
					if (this.selectedProject) {
						this.updateProjectDetails();
					}
				}
			}
		}
	</script>
</x-main-layout>
