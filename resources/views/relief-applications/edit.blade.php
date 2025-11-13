<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('relief-applications.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Edit Relief Application') }}</h1>
		</div>
	</x-slot>

	<div class="max-w-5xl mx-auto">
		<!-- Progress Indicator -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
			<div class="flex items-center justify-between text-sm font-medium text-gray-600 dark:text-gray-400 mb-4">
				<span class="flex items-center">
					<span class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-xs mr-3">✓</span>
					<span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ $reliefApplication->application_type === 'individual' ? __('Individual') : __('Organization') }}</span>
				</span>
				<span class="flex items-center">
					<span class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-xs mr-3">✓</span>
					<span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Location') }}</span>
				</span>
				<span class="flex items-center">
					<span class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-xs mr-3">✓</span>
					<span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Relief Info') }}</span>
				</span>
				<span class="flex items-center">
					<span class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-xs mr-3">✓</span>
					<span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Applicant') }}</span>
				</span>
				<span class="flex items-center">
					<span class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-xs mr-3">!</span>
					<span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Edit Mode') }}</span>
				</span>
			</div>
			<div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
				<div class="bg-gradient-to-r from-green-500 to-orange-500 h-2 rounded-full transition-all duration-300 ease-in-out" style="width: 100%"></div>
			</div>
		</div>

		<!-- Main Form Card -->
		<div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="bg-gradient-to-r from-orange-50 to-yellow-50 dark:from-gray-700 dark:to-gray-800 px-8 py-6 border-b border-gray-200 dark:border-gray-700">
				<div class="flex items-center">
					<div class="flex-shrink-0">
						<div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-yellow-600 rounded-lg flex items-center justify-center">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
							</svg>
						</div>
					</div>
					<div class="ml-4">
						<h3 class="text-xl font-bold text-gray-900 dark:text-white {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Edit Relief Application') }}</h3>
						<p class="text-sm text-gray-600 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Update your relief application details below') }}</p>
					</div>
				</div>
			</div>
			<div class="p-8">
				<form action="{{ route('relief-applications.update', $reliefApplication) }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="reliefApplicationForm()" x-init="init()">
					@csrf
					@method('PUT')

					<!-- Hidden field to maintain application type -->
					<input type="hidden" name="application_type" value="{{ $reliefApplication->application_type }}">

					<!-- Application Type Information Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<div class="flex items-center mb-6">
							<div class="flex-shrink-0">
								<div class="w-8 h-8 {{ $reliefApplication->application_type === 'individual' ? 'bg-gradient-to-r from-green-400 to-green-600' : 'bg-gradient-to-r from-blue-400 to-blue-600' }} rounded-lg flex items-center justify-center">
									@if($reliefApplication->application_type === 'individual')
										<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
										</svg>
									@else
									<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
									</svg>
									@endif
								</div>
							</div>
							<div class="ml-3">
								<h4 class="text-lg font-semibold text-gray-900 dark:text-white {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ $reliefApplication->application_type === 'individual' ? __('Individual Information') : __('Organization Information') }}
								</h4>
								<p class="text-sm text-gray-500 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ $reliefApplication->application_type === 'individual' ? __('Update your personal details') : __('Update your organization details') }}
								</p>
							</div>
						</div>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							@if($reliefApplication->application_type === 'organization')
							<!-- Organization Name -->
							<div class="md:col-span-2">
								<label for="organization_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Organization Name') }} <span class="text-red-500">*</span>
								</label>
								<input type="text" 
									name="organization_name" 
									id="organization_name" 
									value="{{ old('organization_name', $reliefApplication->organization_name) }}"
									class="input-field @error('organization_name') border-red-500 dark:border-red-400 @enderror"
									placeholder="{{ __('Enter organization name') }}"
									required>
								@error('organization_name')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Organization Type -->
							<div>
								<label for="organization_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Organization Type') }}
								</label>
								<select name="organization_type_id" 
									id="organization_type_id" 
									class="input-field @error('organization_type_id') border-red-500 dark:border-red-400 @enderror">
									<option value="">{{ __('Select Organization Type') }}</option>
									@foreach($organizationTypes as $organizationType)
										<option value="{{ $organizationType->id }}" {{ old('organization_type_id', $reliefApplication->organization_type_id) == $organizationType->id ? 'selected' : '' }}>
											{{ localized_attr($organizationType, 'name') }}
										</option>
									@endforeach
								</select>
								@error('organization_type_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

								<!-- Organization Address -->
								<div class="md:col-span-2">
								<label for="organization_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Organization Address') }} <span class="text-red-500">*</span>
								</label>
									<textarea name="organization_address" 
										id="organization_address" 
										rows="3"
										class="input-field @error('organization_address') border-red-500 dark:border-red-400 @enderror"
										placeholder="{{ __('Enter organization address') }}"
										required>{{ old('organization_address', $reliefApplication->organization_address) }}</textarea>
									@error('organization_address')
										<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
									@enderror
								</div>
							@endif

							<!-- Application Date -->
							<div>
								<label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Application Date') }} <span class="text-red-500">*</span>
								</label>
								<input type="date" 
									name="date" 
									id="date" 
									value="{{ old('date', $reliefApplication->date->format('Y-m-d')) }}"
									class="input-field @error('date') border-red-500 dark:border-red-400 @enderror"
									required>
								@error('date')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- NID (for both types) -->
							<div>
								<label for="applicant_nid" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('NID') }} <span class="text-red-500">*</span>
								</label>
								<input type="text" 
									name="applicant_nid" 
									id="applicant_nid" 
									value="{{ old('applicant_nid', $reliefApplication->applicant_nid) }}"
									class="input-field @error('applicant_nid') border-red-500 dark:border-red-400 @enderror"
									placeholder="{{ __('Enter NID number') }}"
									required>
								@error('applicant_nid')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Applicant Name -->
							<div>
								<label for="applicant_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Applicant Name') }} <span class="text-red-500">*</span>
								</label>
								<input type="text" 
									name="applicant_name" 
									id="applicant_name" 
									value="{{ old('applicant_name', $reliefApplication->applicant_name) }}"
									class="input-field @error('applicant_name') border-red-500 dark:border-red-400 @enderror"
									placeholder="{{ __('Enter applicant name') }}"
									required>
								@error('applicant_name')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Applicant Phone -->
							<div>
								<label for="applicant_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Phone Number') }} <span class="text-red-500">*</span>
								</label>
								<input type="tel" 
									name="applicant_phone" 
									id="applicant_phone" 
									value="{{ old('applicant_phone', $reliefApplication->applicant_phone) }}"
									class="input-field @error('applicant_phone') border-red-500 dark:border-red-400 @enderror"
									placeholder="{{ __('Enter phone number') }}"
									required>
								@error('applicant_phone')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Applicant Designation (for organizations) -->
							@if($reliefApplication->application_type === 'organization')
							<div>
								<label for="applicant_designation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Designation') }}
								</label>
								<input type="text" 
									name="applicant_designation" 
									id="applicant_designation" 
									value="{{ old('applicant_designation', $reliefApplication->applicant_designation) }}"
									class="input-field @error('applicant_designation') border-red-500 dark:border-red-400 @enderror"
									placeholder="{{ __('Enter designation') }}">
								@error('applicant_designation')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
							@endif

							<!-- Applicant Address -->
							<div class="md:col-span-2">
								<label for="applicant_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Applicant Address') }} <span class="text-red-500">*</span>
								</label>
								<textarea name="applicant_address" 
									id="applicant_address" 
									rows="3"
									class="input-field @error('applicant_address') border-red-500 dark:border-red-400 @enderror"
									placeholder="{{ __('Enter applicant address') }}"
									required>{{ old('applicant_address', $reliefApplication->applicant_address) }}</textarea>
								@error('applicant_address')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
						</div>
					</div>

					<!-- Relief Information Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Relief Information') }}</h4>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<!-- Relief Type Selection -->
							<div class="md:col-span-2">
								<label for="relief_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Relief Type') }} (ত্রাণের ধরন) <span class="text-red-500">*</span>
								</label>
								<select name="relief_type_id" 
									id="relief_type_id" 
									class="input-field @error('relief_type_id') border-red-500 dark:border-red-400 @enderror"
									required
									x-model="selectedReliefType"
									@change="loadProjectsByReliefType()">
									<option value="">{{ __('Select Relief Type') }} (ত্রাণের ধরন নির্বাচন করুন)</option>
									@foreach($reliefTypes as $reliefType)
										<option value="{{ $reliefType->id }}" {{ old('relief_type_id', $reliefApplication->relief_type_id) == $reliefType->id ? 'selected' : '' }}>
											{{ $reliefType->name_bn ?? $reliefType->name }}
										</option>
									@endforeach
								</select>
								<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
									{{ __('Select the type of relief you are applying for') }}
								</p>
								@error('relief_type_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Project Selection -->
							<div class="md:col-span-2">
								<label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Project') }} (প্রকল্প) <span class="text-red-500">*</span>
								</label>
								<select name="project_id" 
									id="project_id" 
									class="input-field @error('project_id') border-red-500 dark:border-red-400 @enderror"
									required
									x-model="selectedProject"
									@change="updateProjectDetails()"
									:disabled="!selectedReliefType">
									<option value="">{{ __('Select Project') }} (প্রকল্প নির্বাচন করুন)</option>
									<template x-for="project in filteredProjects" :key="project.id">
										<option :value="project.id"
											:data-unit="project.relief_type_unit_bn || project.relief_type_unit"
											:data-relief-type="project.relief_type_name_bn || project.relief_type_name"
											:selected="project.id == {{ old('project_id', $reliefApplication->project_id) ?? 'null' }}"
											x-text="project.name_bn || project.name"></option>
									</template>
								</select>
								<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
									{{ __('Select relief type first to see available projects') }}
								</p>
								@error('project_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Amount Requested -->
							<div class="md:col-span-2">
								<label for="amount_requested" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Amount Requested') }}@if(!app()->isLocale('bn')) (অনুরোধকৃত পরিমাণ)@endif <span class="text-red-500">*</span>
								</label>
								<div class="relative">
									<input type="number" 
										name="amount_requested" 
										id="amount_requested" 
										value="{{ old('amount_requested', $reliefApplication->amount_requested) }}"
										class="input-field @error('amount_requested') border-red-500 dark:border-red-400 @enderror pr-20"
										placeholder="Enter requested amount"
										min="0.01"
										step="0.01"
										required>
									<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
										<span class="text-gray-500 dark:text-gray-400 text-sm {{ app()->isLocale('bn') ? 'font-sans' : '' }}" x-text="projectUnit || '{{ __('Select project first') }}@if(!app()->isLocale('bn')) (প্রকল্প নির্বাচন করুন)@endif'"></span>
									</div>
								</div>
								<p class="mt-1 text-xs text-gray-500 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Amount will be displayed with appropriate unit based on selected project\'s relief type') }}@if(!app()->isLocale('bn')) (নির্বাচিত প্রকল্পের ত্রাণের ধরনের উপর ভিত্তি করে পরিমাণ উপযুক্ত এককের সাথে প্রদর্শিত হবে)@endif
								</p>
								@error('amount_requested')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Subject -->
							<div>
								<label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Subject') }}@if(!app()->isLocale('bn')) (বিষয়)@endif <span class="text-red-500">*</span>
								</label>
								<input type="text" 
									name="subject" 
									id="subject" 
									value="{{ old('subject', $reliefApplication->subject) }}"
									class="input-field @error('subject') border-red-500 dark:border-red-400 @enderror"
									placeholder="Brief description of your relief request"
									required>
								@error('subject')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Details -->
							<div class="md:col-span-2">
								<label for="details" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Application Details') }}@if(!app()->isLocale('bn')) (আবেদনের বিস্তারিত)@endif <span class="text-red-500">*</span>
								</label>
								<textarea name="details" 
									id="details" 
									rows="5"
									class="input-field @error('details') border-red-500 dark:border-red-400 @enderror"
									placeholder="Provide detailed information about your relief request"
									required>{{ old('details', $reliefApplication->details) }}</textarea>
								@error('details')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
						</div>
					</div>

					<!-- Location Information Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Location Information') }}@if(!app()->isLocale('bn')) (অবস্থানের তথ্য)@endif</h4>
						
						<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
							<!-- Zilla Selection -->
							<div>
								<label for="zilla_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Zilla (District)') }}@if(!app()->isLocale('bn')) (জেলা)@endif <span class="text-red-500">*</span>
								</label>
								<select name="zilla_id" 
									id="zilla_id" 
									x-model="selectedZilla"
									@change="loadUpazilas()"
									class="input-field @error('zilla_id') border-red-500 dark:border-red-400 @enderror"
									required>
									<option value="">{{ __('Select a Zilla') }}@if(!app()->isLocale('bn')) (জেলা নির্বাচন করুন)@endif</option>
									@foreach($zillas as $zilla)
										<option value="{{ $zilla->id }}" {{ old('zilla_id', $reliefApplication->zilla_id) == $zilla->id ? 'selected' : '' }}>
											{{ localized_attr($zilla, 'name') }}
										</option>
									@endforeach
								</select>
								@error('zilla_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Upazila Selection -->
							<div>
								<label for="upazila_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Upazila (Sub-district)') }}@if(!app()->isLocale('bn')) (উপজেলা)@endif <span class="text-red-500">*</span>
								</label>
								<select name="upazila_id" 
									id="upazila_id" 
									x-model="selectedUpazila"
									@change="loadUnions()"
									class="input-field @error('upazila_id') border-red-500 dark:border-red-400 @enderror"
									:disabled="!selectedZilla"
									required>
									<option value="">{{ __('Select a Upazila') }}@if(!app()->isLocale('bn')) (উপজেলা নির্বাচন করুন)@endif</option>
									<template x-for="upazila in upazilas" :key="upazila.id">
										<option :value="upazila.id" :selected="upazila.id == selectedUpazila" x-text="upazila.name_bn || upazila.name"></option>
									</template>
								</select>
								@error('upazila_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Union Selection -->
							<div>
								<label for="union_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Union') }}@if(!app()->isLocale('bn')) (ইউনিয়ন)@endif <span class="text-red-500">*</span>
								</label>
								<select name="union_id" 
									id="union_id" 
									x-model="selectedUnion"
									@change="loadWards()"
									class="input-field @error('union_id') border-red-500 dark:border-red-400 @enderror"
									:disabled="!selectedUpazila"
									required>
									<option value="">{{ __('Select a Union') }}@if(!app()->isLocale('bn')) (ইউনিয়ন নির্বাচন করুন)@endif</option>
									<template x-for="union in unions" :key="union.id">
										<option :value="union.id" :selected="union.id == selectedUnion" x-text="union.name_bn || union.name"></option>
									</template>
								</select>
								@error('union_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>

							<!-- Ward Selection -->
							<div>
								<label for="ward_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Ward') }}@if(!app()->isLocale('bn')) (ওয়ার্ড)@endif
								</label>
								<select name="ward_id" 
									id="ward_id" 
									x-model="selectedWard"
									class="input-field @error('ward_id') border-red-500 dark:border-red-400 @enderror"
									:disabled="!selectedUnion">
									<option value="">{{ __('Select a Ward') }}@if(!app()->isLocale('bn')) (ওয়ার্ড নির্বাচন করুন)@endif</option>
									<template x-for="ward in wards" :key="ward.id">
										<option :value="ward.id" :selected="ward.id == selectedWard" x-text="ward.name_bn || ward.name"></option>
									</template>
								</select>
								@error('ward_id')
									<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
								@enderror
							</div>
						</div>
					</div>

					<!-- General Comment Section -->
					<div class="border-b border-gray-200 dark:border-gray-700 pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('General Comment') }}@if(!app()->isLocale('bn')) (সাধারণ মন্তব্য)@endif</h4>
						
						<div>
							<label for="general_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
								{{ __('General Comment') }}@if(!app()->isLocale('bn')) (সাধারণ মন্তব্য)@endif
							</label>
							<textarea name="general_comment" 
								id="general_comment" 
								rows="4"
								class="input-field @error('general_comment') border-red-500 dark:border-red-400 @enderror"
								placeholder="{{ __('Enter any additional comments or notes') }}@if(!app()->isLocale('bn')) (যেকোনো অতিরিক্ত মন্তব্য বা নোট লিখুন)@endif">{{ old('general_comment', $reliefApplication->general_comment) }}</textarea>
							@error('general_comment')
								<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
							@enderror
						</div>
					</div>

					<!-- File Upload Section -->
					<div class="pb-8">
						<h4 class="text-lg font-medium text-gray-900 dark:text-white mb-6 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Supporting Documents') }}@if(!app()->isLocale('bn')) (সহায়ক নথি)@endif</h4>
						
						<!-- Current File Display -->
						@if($reliefApplication->application_file)
							<div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
								<div class="flex items-center">
									<svg class="h-8 w-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
									</svg>
									<div>
										<p class="text-sm font-medium text-gray-900 dark:text-white">Current File:</p>
										<a href="{{ Storage::url($reliefApplication->application_file) }}" target="_blank" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
											{{ basename($reliefApplication->application_file) }}
										</a>
									</div>
								</div>
							</div>
						@endif
						
						<div>
							<label for="application_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
								{{ $reliefApplication->application_file ? __('Replace Application File') . (!app()->isLocale('bn') ? ' (আবেদন ফাইল পরিবর্তন)' : '') : __('Application File') . (!app()->isLocale('bn') ? ' (আবেদন ফাইল)' : '') }}
							</label>
							<div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-gray-400 dark:hover:border-gray-500 transition-colors">
								<div class="space-y-1 text-center">
									<svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
										<path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
									</svg>
									<div class="flex text-sm text-gray-600 dark:text-gray-400">
										<label for="application_file" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
											<span>{{ $reliefApplication->application_file ? 'Upload new file' : 'Upload a file' }}</span>
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
									@if($reliefApplication->application_file)
										<p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
											Leave empty to keep current file
										</p>
									@endif
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

					<!-- Update Section -->
					<div class="bg-gradient-to-r from-gray-50 to-orange-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-600">
						<div class="flex items-center justify-between">
							<div>
								<h5 class="text-lg font-semibold text-gray-900 dark:text-white {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Ready to Update?') }}@if(!app()->isLocale('bn')) (আপডেট করতে প্রস্তুত?)@endif</h5>
								<p class="text-sm text-gray-600 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Please review your changes before updating the application.') }}@if(!app()->isLocale('bn')) (আবেদন আপডেট করার আগে আপনার পরিবর্তনগুলি পর্যালোচনা করুন।)@endif</p>
							</div>
							<div class="flex space-x-3">
								<a href="{{ route('relief-applications.index') }}" class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
									{{ __('Cancel') }}@if(!app()->isLocale('bn')) (বাতিল)@endif
								</a>
								<button type="submit" class="bg-gradient-to-r from-orange-500 to-yellow-600 hover:from-orange-600 hover:to-yellow-700 text-white font-semibold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center space-x-2">
									<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
									</svg>
									<span class="{{ app()->isLocale('bn') ? 'font-sans' : '' }}">{{ __('Update Application') }}@if(!app()->isLocale('bn')) (আবেদন আপডেট করুন)@endif</span>
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
				selectedZilla: '{{ old('zilla_id', $reliefApplication->zilla_id) }}',
				selectedUpazila: '{{ old('upazila_id', $reliefApplication->upazila_id) }}',
				selectedUnion: '{{ old('union_id', $reliefApplication->union_id) }}',
				selectedWard: '{{ old('ward_id', $reliefApplication->ward_id) }}',
				originalZilla: '{{ old('zilla_id', $reliefApplication->zilla_id) }}',
				originalUpazila: '{{ old('upazila_id', $reliefApplication->upazila_id) }}',
				originalUnion: '{{ old('union_id', $reliefApplication->union_id) }}',
				originalWard: '{{ old('ward_id', $reliefApplication->ward_id) }}',
				upazilas: @json($upazilas),
				unions: @json($unions),
				wards: @json($wards),
				selectedFile: null,
				selectedProject: '{{ old('project_id', $reliefApplication->project_id) }}',
				projectUnit: '',
				selectedReliefType: '{{ old('relief_type_id', $reliefApplication->relief_type_id) }}',
				projects: @json($projects ?? []),
				filteredProjects: [],
				
				loadUpazilas() {
					if (this.selectedZilla) {
						fetch(`/upazilas-by-zilla/${this.selectedZilla}`)
							.then(response => response.json())
							.then(data => {
								this.upazilas = data;
								// Only reset dependent fields if we're changing zilla (not initializing)
								if (this.originalZilla && this.originalZilla != this.selectedZilla) {
									this.selectedUpazila = '';
									this.selectedUnion = '';
									this.selectedWard = '';
									this.unions = [];
									this.wards = [];
								}
								// If we have a selected upazila, load its dependent data
								if (this.selectedUpazila) {
									this.loadUnions();
								}
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
								// Only reset dependent fields if we're changing upazila (not initializing)
								if (this.originalUpazila && this.originalUpazila != this.selectedUpazila) {
									this.selectedUnion = '';
									this.selectedWard = '';
									this.wards = [];
								}
								// If we have a selected union, load its dependent data
								if (this.selectedUnion) {
									this.loadWards();
								}
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
								// Only reset ward if we're changing union (not initializing)
								if (this.originalUnion && this.originalUnion != this.selectedUnion) {
									this.selectedWard = '';
								}
							})
							.catch(error => {
								console.error('Error loading wards:', error);
							});
					} else {
						this.wards = [];
						this.selectedWard = '';
					}
				},
				
				loadProjectsByReliefType() {
					if (this.selectedReliefType) {
						// Filter projects by selected relief type (client-side filtering)
						this.filteredProjects = this.projects.filter(project =>
							project.relief_type_id == this.selectedReliefType
						);

						// Alternative: Use API endpoint for server-side filtering (uncomment if needed)
						// fetch(`/projects-by-relief-type?relief_type_id=${this.selectedReliefType}`)
						// 	.then(response => response.json())
						// 	.then(data => {
						// 		this.filteredProjects = data;
						// 	})
						// 	.catch(error => {
						// 		console.error('Error loading projects:', error);
						// 	});

						// Reset selected project when relief type changes
						this.selectedProject = '';
						this.projectUnit = '';
					} else {
						this.filteredProjects = [];
						this.selectedProject = '';
						this.projectUnit = '';
					}
				},
				
				updateProjectDetails() {
					const selectElement = document.getElementById('project_id');
					const selectedOption = selectElement.options[selectElement.selectedIndex];
					
					if (selectedOption.value) {
						this.projectUnit = selectedOption.dataset.unit;
					} else {
						this.projectUnit = '';
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
					// Initialize projects by relief type if already selected
					if (this.selectedReliefType) {
						this.loadProjectsByReliefType();
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
