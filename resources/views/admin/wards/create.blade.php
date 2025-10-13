<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.wards.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('নতুন ওয়ার্ড তৈরি করুন') }}</h1>
		</div>
	</x-slot>

	<div class="max-w-2xl mx-auto">
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('ওয়ার্ড তথ্য') }}</h3>
			</div>
			<div class="p-6">
				<form action="{{ route('admin.wards.store') }}" method="POST" class="space-y-6" x-data="wardForm()">
					@csrf

					<!-- Zilla Selection -->
					<div>
						<label for="zilla_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							{{ __('জেলা') }} <span class="text-red-500">*</span>
						</label>
						<select name="zilla_id" 
							id="zilla_id" 
							x-model="selectedZilla"
							@change="loadUpazilas()"
							class="input-field @error('zilla_id') border-red-500 dark:border-red-400 @enderror"
							required>
							<option value="">{{ __('জেলা নির্বাচন করুন') }}</option>
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
							{{ __('উপজেলা') }} <span class="text-red-500">*</span>
						</label>
						<select name="upazila_id" 
							id="upazila_id" 
							x-model="selectedUpazila"
							@change="loadUnions()"
							class="input-field @error('upazila_id') border-red-500 dark:border-red-400 @enderror"
							:disabled="!selectedZilla"
							required>
							<option value="">{{ __('উপজেলা নির্বাচন করুন') }}</option>
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
							{{ __('ইউনিয়ন') }} <span class="text-red-500">*</span>
						</label>
						<select name="union_id" 
							id="union_id" 
							x-model="selectedUnion"
							class="input-field @error('union_id') border-red-500 dark:border-red-400 @enderror"
							:disabled="!selectedUpazila"
							required>
							<option value="">{{ __('ইউনিয়ন নির্বাচন করুন') }}</option>
							<template x-for="union in unions" :key="union.id">
								<option :value="union.id" x-text="union.name + ' (' + union.name_bn + ')'"></option>
							</template>
						</select>
						@error('union_id')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Name -->
					<div>
						<label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							{{ __('নাম') }} <span class="text-red-500">*</span>
						</label>
						<input type="text" 
							name="name" 
							id="name" 
							value="{{ old('name') }}"
							class="input-field @error('name') border-red-500 dark:border-red-400 @enderror"
							placeholder="{{ __('ওয়ার্ডের নাম লিখুন') }}"
							required>
						@error('name')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Bengali Name -->
					<div>
						<label for="name_bn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							{{ __('বাংলা নাম') }}
						</label>
						<input type="text" 
							name="name_bn" 
							id="name_bn" 
							value="{{ old('name_bn') }}"
							class="input-field @error('name_bn') border-red-500 dark:border-red-400 @enderror"
							placeholder="{{ __('বাংলা নাম লিখুন') }}">
						@error('name_bn')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Code -->
					<div>
						<label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							{{ __('কোড') }} <span class="text-red-500">*</span>
						</label>
						<input type="text" 
							name="code" 
							id="code" 
							value="{{ old('code') }}"
							class="input-field @error('code') border-red-500 dark:border-red-400 @enderror"
							placeholder="{{ __('অনন্য কোড লিখুন (যেমন, BOG_WARD_01)') }}"
							required>
						@error('code')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Status -->
					<div>
						<label class="flex items-center">
							<input type="checkbox" 
								name="is_active" 
								value="1"
								{{ old('is_active', true) ? 'checked' : '' }}
								class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
							<span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('সক্রিয়') }}</span>
						</label>
					</div>

					<!-- Submit Buttons -->
					<div class="flex justify-end space-x-3">
						<a href="{{ route('admin.wards.index') }}" class="btn-secondary">
							{{ __('বাতিল') }}
						</a>
						<button type="submit" class="btn-primary">
							{{ __('ওয়ার্ড তৈরি করুন') }}
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		function wardForm() {
			return {
				selectedZilla: '{{ old('zilla_id') }}',
				selectedUpazila: '{{ old('upazila_id') }}',
				selectedUnion: '{{ old('union_id') }}',
				upazilas: @json($upazilas->toArray()),
				unions: @json($unions->toArray()),
				
				loadUpazilas() {
					if (this.selectedZilla) {
						fetch(`/admin/upazilas-by-zilla/${this.selectedZilla}`)
							.then(response => response.json())
							.then(data => {
								this.upazilas = data;
								this.selectedUpazila = '';
								this.selectedUnion = '';
								this.unions = [];
							})
							.catch(error => {
								console.error('Error loading upazilas:', error);
							});
					} else {
						this.upazilas = [];
						this.selectedUpazila = '';
						this.selectedUnion = '';
						this.unions = [];
					}
				},
				
				loadUnions() {
					if (this.selectedUpazila) {
						fetch(`/admin/unions-by-upazila/${this.selectedUpazila}`)
							.then(response => response.json())
							.then(data => {
								this.unions = data;
								this.selectedUnion = '';
							})
							.catch(error => {
								console.error('Error loading unions:', error);
							});
					} else {
						this.unions = [];
						this.selectedUnion = '';
					}
				},
				
				init() {
					if (this.selectedZilla) {
						this.loadUpazilas();
					}
					if (this.selectedUpazila) {
						this.loadUnions();
					}
				}
			}
		}
	</script>
</x-main-layout>
