<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.unions.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Union: {{ $union->name }}</h1>
		</div>
	</x-slot>

	<div class="max-w-2xl mx-auto">
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Union Information</h3>
			</div>
			<div class="p-6">
				<form action="{{ route('admin.unions.update', $union) }}" method="POST" class="space-y-6" x-data="unionForm()">
					@csrf
					@method('PUT')

					<!-- Zilla Selection -->
					<div>
						<label for="zilla_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Zilla <span class="text-red-500">*</span>
						</label>
						<select name="zilla_id" 
							id="zilla_id" 
							x-model="selectedZilla"
							@change="loadUpazilas()"
							class="input-field @error('zilla_id') border-red-500 dark:border-red-400 @enderror"
							required>
							<option value="">Select a Zilla</option>
							@foreach($zillas as $zilla)
								<option value="{{ $zilla->id }}" {{ old('zilla_id', $union->upazila->zilla_id) == $zilla->id ? 'selected' : '' }}>
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
							Upazila <span class="text-red-500">*</span>
						</label>
						<select name="upazila_id" 
							id="upazila_id" 
							x-model="selectedUpazila"
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

					<!-- Name -->
					<div>
						<label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Name <span class="text-red-500">*</span>
						</label>
						<input type="text" 
							name="name" 
							id="name" 
							value="{{ old('name', $union->name) }}"
							class="input-field @error('name') border-red-500 dark:border-red-400 @enderror"
							placeholder="Enter union name"
							required>
						@error('name')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Bengali Name -->
					<div>
						<label for="name_bn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Bengali Name
						</label>
						<input type="text" 
							name="name_bn" 
							id="name_bn" 
							value="{{ old('name_bn', $union->name_bn) }}"
							class="input-field @error('name_bn') border-red-500 dark:border-red-400 @enderror"
							placeholder="Enter Bengali name">
						@error('name_bn')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Code -->
					<div>
						<label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Code <span class="text-red-500">*</span>
						</label>
						<input type="text" 
							name="code" 
							id="code" 
							value="{{ old('code', $union->code) }}"
							class="input-field @error('code') border-red-500 dark:border-red-400 @enderror"
							placeholder="Enter unique code (e.g., BOG_MUNI)"
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
								{{ old('is_active', $union->is_active) ? 'checked' : '' }}
								class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
							<span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
						</label>
					</div>

					<!-- Submit Buttons -->
					<div class="flex justify-end space-x-3">
						<a href="{{ route('admin.unions.show', $union) }}" class="btn-secondary">
							Cancel
						</a>
						<button type="submit" class="btn-primary">
							Update Union
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		function unionForm() {
			return {
				selectedZilla: '{{ old('zilla_id', $union->upazila->zilla_id) }}',
				selectedUpazila: '{{ old('upazila_id', $union->upazila_id) }}',
				upazilas: @json($upazilas->toArray()),
				
				loadUpazilas() {
					if (this.selectedZilla) {
						fetch(`/admin/upazilas-by-zilla/${this.selectedZilla}`)
							.then(response => response.json())
							.then(data => {
								this.upazilas = data;
								// Keep current upazila if it belongs to selected zilla
								const currentUpazila = data.find(u => u.id == this.selectedUpazila);
								if (!currentUpazila) {
									this.selectedUpazila = '';
								}
							})
							.catch(error => {
								console.error('Error loading upazilas:', error);
							});
					} else {
						this.upazilas = [];
						this.selectedUpazila = '';
					}
				},
				
				init() {
					if (this.selectedZilla) {
						this.loadUpazilas();
					}
				}
			}
		}
	</script>
</x-main-layout>
