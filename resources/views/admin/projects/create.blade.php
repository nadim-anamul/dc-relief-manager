<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.projects.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Project</h1>
		</div>
	</x-slot>

	<div class="max-w-2xl mx-auto">
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Project Information</h3>
			</div>
			<div class="p-6">
				<form action="{{ route('admin.projects.store') }}" method="POST" class="space-y-6">
					@csrf

					<!-- Project Name -->
					<div>
						<label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Project Name <span class="text-red-500">*</span>
						</label>
						<input type="text" 
							name="name" 
							id="name" 
							value="{{ old('name') }}"
							class="input-field @error('name') border-red-500 dark:border-red-400 @enderror"
							placeholder="Enter project name"
							required>
						@error('name')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Economic Year Selection -->
					<div>
						<label for="economic_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Economic Year <span class="text-red-500">*</span>
						</label>
						<select name="economic_year_id" 
							id="economic_year_id" 
							class="input-field @error('economic_year_id') border-red-500 dark:border-red-400 @enderror"
							required>
							<option value="">Select an Economic Year</option>
							@foreach($economicYears as $economicYear)
								<option value="{{ $economicYear->id }}" {{ old('economic_year_id') == $economicYear->id ? 'selected' : '' }}>
									{{ $economicYear->name }} ({{ $economicYear->name_bn }})
									@if($economicYear->is_current) - Current @endif
								</option>
							@endforeach
						</select>
						@error('economic_year_id')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Relief Type Selection -->
					<div>
						<label for="relief_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Relief Type <span class="text-red-500">*</span>
						</label>
						<select name="relief_type_id" 
							id="relief_type_id" 
							class="input-field @error('relief_type_id') border-red-500 dark:border-red-400 @enderror"
							required>
							<option value="">Select a Relief Type</option>
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

					<!-- Allocated Amount -->
					<div>
						<label for="allocated_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Allocated Amount <span class="text-red-500">*</span>
						</label>
						<div class="relative">
							<input type="number" 
								name="allocated_amount" 
								id="allocated_amount" 
								value="{{ old('allocated_amount') }}"
								class="input-field @error('allocated_amount') border-red-500 dark:border-red-400 @enderror pr-20"
								placeholder="Enter allocated amount"
								min="0.01"
								step="0.01"
								required>
							<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
								<span class="text-gray-500 dark:text-gray-400 text-sm" id="unit-display">Unit</span>
							</div>
						</div>
						<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
							Amount allocated for <span id="relief-type-display">this relief type</span> in this economic year
						</p>
						@error('allocated_amount')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Remarks -->
					<div>
						<label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Remarks
						</label>
						<textarea name="remarks" 
							id="remarks" 
							rows="4"
							class="input-field @error('remarks') border-red-500 dark:border-red-400 @enderror"
							placeholder="Enter any additional remarks or notes">{{ old('remarks') }}</textarea>
						@error('remarks')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Submit Buttons -->
					<div class="flex justify-end space-x-3">
						<a href="{{ route('admin.projects.index') }}" class="btn-secondary">
							Cancel
						</a>
						<button type="submit" class="btn-primary">
							Create Project
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		// Relief types data for unit display
		const reliefTypes = {
			@foreach($reliefTypes as $reliefType)
				{{ $reliefType->id }}: {
					unit: '{{ $reliefType->unit ?? "Unit" }}',
					unit_bn: '{{ $reliefType->unit_bn ?? "" }}'
				},
			@endforeach
		};

		// Update relief type display and unit when relief type changes
		document.getElementById('relief_type_id').addEventListener('change', function() {
			const reliefTypeId = this.value;
			const reliefTypeDisplay = document.getElementById('relief-type-display');
			const unitDisplay = document.getElementById('unit-display');
			
			if (reliefTypeId && reliefTypes[reliefTypeId]) {
				const selectedOption = this.options[this.selectedIndex];
				const reliefTypeName = selectedOption.textContent;
				const unit = reliefTypes[reliefTypeId].unit_bn || reliefTypes[reliefTypeId].unit;
				
				reliefTypeDisplay.textContent = reliefTypeName;
				unitDisplay.textContent = unit;
				
				// Update placeholder and validation based on unit type
				const amountInput = document.getElementById('allocated_amount');
				if (unit === 'টাকা' || unit === 'Taka') {
					amountInput.placeholder = 'Enter amount in Taka';
					amountInput.min = '1000';
				} else {
					amountInput.placeholder = 'Enter quantity';
					amountInput.min = '0.01';
				}
			} else {
				reliefTypeDisplay.textContent = 'this relief type';
				unitDisplay.textContent = 'Unit';
			}
		});

		// Initialize relief type display on page load
		document.addEventListener('DOMContentLoaded', function() {
			const reliefTypeSelect = document.getElementById('relief_type_id');
			if (reliefTypeSelect.value) {
				reliefTypeSelect.dispatchEvent(new Event('change'));
			}
		});
	</script>
</x-main-layout>
