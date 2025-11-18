<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.projects.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Create New Project') }}</h1>
		</div>
	</x-slot>

	<div class="max-w-2xl mx-auto">
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Project Information') }}</h3>
			</div>
			<div class="p-6">
				<form action="{{ route('admin.projects.store') }}" method="POST" class="space-y-6" x-data="projectForm()" @submit="handleFormSubmit($event)">
					@csrf

					<!-- Project Name -->
					<div>
					<label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						{{ __('Distribution Name') }} <span class="text-red-500">*</span>
					</label>
						<input type="text" 
							name="name" 
							id="name" 
							value="{{ old('name') }}"
							class="input-field @error('name') border-red-500 dark:border-red-400 @enderror"
							placeholder="{{ __('Enter project name') }}"
							required>
						@error('name')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Economic Year Selection -->
					<div>
					<label for="economic_year_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						{{ __('Economic Year') }} <span class="text-red-500">*</span>
					</label>
						<select name="economic_year_id" 
							id="economic_year_id" 
							class="input-field @error('economic_year_id') border-red-500 dark:border-red-400 @enderror"
							required>
							<option value="">{{ __('Select an Economic Year') }}</option>
							@foreach($economicYears as $economicYear)
								<option value="{{ $economicYear->id }}" {{ old('economic_year_id') == $economicYear->id ? 'selected' : '' }}>
								{{ $economicYear->name_bn }}
								@if($economicYear->is_current) - {{ __('Current') }} @endif
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
						{{ __('Relief Type') }} <span class="text-red-500">*</span>
					</label>
						<select name="relief_type_id" 
							id="relief_type_id" 
							class="input-field @error('relief_type_id') border-red-500 dark:border-red-400 @enderror"
							required>
							<option value="">{{ __('Select a Relief Type') }}</option>
							@foreach($reliefTypes as $reliefType)
								<option value="{{ $reliefType->id }}" {{ old('relief_type_id') == $reliefType->id ? 'selected' : '' }}>
									{{ $reliefType->name_bn }}
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
						{{ __('Allocated Amount') }} <span class="text-red-500">*</span>
					</label>
						<div class="relative">
							<input type="text" 
								id="allocated_amount" 
								x-model="allocatedAmountDisplay"
								@input="handleAllocatedAmountInput($event)"
								@blur="handleAllocatedAmountBlur($event)"
								value="{{ old('allocated_amount') ? bn_number(old('allocated_amount')) : '' }}"
								class="input-field @error('allocated_amount') border-red-500 dark:border-red-400 @enderror pr-20"
								placeholder="{{ __('Enter allocated amount') }}"
								required>
							<!-- Hidden input to store English number for submission -->
							<input type="hidden" name="allocated_amount" id="allocated_amount_en" value="{{ old('allocated_amount') }}">
							<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
								<span class="text-gray-500 dark:text-gray-400 text-sm" id="unit-display">{{ __('Unit') }}</span>
							</div>
						</div>
					<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
						{{ __('Amount allocated for') }} <span id="relief-type-display">{{ __('this relief type') }}</span>
					</p>
						@error('allocated_amount')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Remarks -->
					<div>
					<label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						{{ __('Remarks') }}
					</label>
						<textarea name="remarks" 
							id="remarks" 
							rows="4"
							class="input-field @error('remarks') border-red-500 dark:border-red-400 @enderror"
							placeholder="{{ __('Enter any additional remarks or notes') }}">{{ old('remarks') }}</textarea>
						@error('remarks')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Ministry Address -->
					<div>
					<label for="ministry_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						{{ __('Ministry Address') }}
					</label>
						<textarea name="ministry_address" 
							id="ministry_address" 
							rows="3"
							class="input-field @error('ministry_address') border-red-500 dark:border-red-400 @enderror"
							placeholder="{{ __('Enter ministry address') }}">{{ old('ministry_address') }}</textarea>
						@error('ministry_address')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Office Order Number -->
					<div>
					<label for="office_order_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						{{ __('Office Order Number') }}
					</label>
						<input type="text" 
							name="office_order_number" 
							id="office_order_number" 
							value="{{ old('office_order_number') }}"
							class="input-field @error('office_order_number') border-red-500 dark:border-red-400 @enderror"
							placeholder="{{ __('Enter office order number') }}">
						@error('office_order_number')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Office Order Date -->
					<div>
					<label for="office_order_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						{{ __('Office Order Date') }}
					</label>
						<input type="date" 
							name="office_order_date" 
							id="office_order_date" 
							value="{{ old('office_order_date') }}"
							class="input-field @error('office_order_date') border-red-500 dark:border-red-400 @enderror">
						@error('office_order_date')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Submit Buttons -->
					<div class="flex justify-end space-x-3">
					<a href="{{ route('admin.projects.index') }}" class="btn-secondary">
						{{ __('Cancel') }}
					</a>
					<button type="submit" class="btn-primary">
						{{ __('Create Project') }}
					</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		function projectForm() {
			return {
				allocatedAmountDisplay: '{{ old('allocated_amount') ? bn_number(old('allocated_amount')) : '' }}',
				
				// Relief types data for unit display
				reliefTypes: {
					@foreach($reliefTypes as $reliefType)
						{{ $reliefType->id }}: {
							unit: '{{ $reliefType->unit_bn ?? $reliefType->unit ?? "Unit" }}',
							unit_bn: '{{ $reliefType->unit_bn ?? "" }}'
						},
					@endforeach
				},
				
				// Convert English numbers to Bangla
				enToBanglaNumber(value) {
					if (!value) return '';
					const map = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
					return String(value).replace(/[0-9]/g, function(match) {
						return map[match] || match;
					});
				},
				
				// Convert Bangla numbers to English
				banglaToEnNumber(value) {
					if (!value) return '';
					const map = {'০':'0','১':'1','২':'2','৩':'3','৪':'4','৫':'5','৬':'6','৭':'7','৮':'8','৯':'9'};
					return String(value).replace(/[০-৯]/g, function(match) {
						return map[match] || match;
					});
				},
				
				// Handle allocated amount input - allow both Bangla and English numbers
				handleAllocatedAmountInput(event) {
					let value = event.target.value;
					// Remove any characters that are not digits (Bangla or English) or decimal point
					value = value.replace(/[^০-৯0-9.]/g, '');
					// Ensure only one decimal point
					const parts = value.split('.');
					if (parts.length > 2) {
						value = parts[0] + '.' + parts.slice(1).join('');
					}
					event.target.value = value;
					this.allocatedAmountDisplay = value;
					
					// Convert to English for hidden field (for form submission)
					const englishValue = this.banglaToEnNumber(value);
					const hiddenInput = document.getElementById('allocated_amount_en');
					if (hiddenInput) {
						hiddenInput.value = englishValue;
					}
				},
				
				// Handle allocated amount blur - convert to Bangla for display
				handleAllocatedAmountBlur(event) {
					let value = event.target.value;
					if (!value) return;
					
					// Convert to English first for validation
					const englishValue = this.banglaToEnNumber(value);
					
					// Validate it's a valid number
					const numValue = parseFloat(englishValue);
					if (isNaN(numValue) || numValue < 0.01) {
						// Invalid value, show error
						event.target.classList.add('border-red-500');
						return;
					}
					
					event.target.classList.remove('border-red-500');
					
					// Preserve decimal places from user input, or default to 2
					let formattedValue;
					if (englishValue.includes('.')) {
						const decimalPart = englishValue.split('.')[1];
						// Preserve original decimal places, minimum 1 if decimal point exists
						const decimalPlaces = Math.max(1, decimalPart.length);
						formattedValue = numValue.toFixed(Math.min(decimalPlaces, 10)); // Max 10 decimal places
					} else {
						// No decimal point, keep as integer or default to 2 decimal places
						formattedValue = numValue.toFixed(2);
					}
					
					// Convert to Bangla for display
					this.allocatedAmountDisplay = this.enToBanglaNumber(formattedValue);
					event.target.value = this.allocatedAmountDisplay;
					
					// Update hidden field with English value (for form submission)
					const hiddenInput = document.getElementById('allocated_amount_en');
					if (hiddenInput) {
						hiddenInput.value = formattedValue;
					}
				},
				
				// Handle form submission - ensure allocated amount is converted to English
				handleFormSubmit(event) {
					const allocatedAmountInput = document.getElementById('allocated_amount');
					const hiddenInput = document.getElementById('allocated_amount_en');
					
					if (allocatedAmountInput && hiddenInput && allocatedAmountInput.value) {
						// Convert to English if not already done
						const englishValue = this.banglaToEnNumber(allocatedAmountInput.value);
						const numValue = parseFloat(englishValue);
						
						if (!isNaN(numValue) && numValue >= 0.01) {
							// Format the value
							let formattedValue;
							if (englishValue.includes('.')) {
								const decimalPart = englishValue.split('.')[1];
								const decimalPlaces = Math.max(1, decimalPart.length);
								formattedValue = numValue.toFixed(Math.min(decimalPlaces, 10));
							} else {
								formattedValue = numValue.toFixed(2);
							}
							hiddenInput.value = formattedValue;
						}
					}
					
					// Allow form to submit normally
					return true;
				},
				
				// Update relief type display and unit when relief type changes
				updateReliefType() {
					const reliefTypeSelect = document.getElementById('relief_type_id');
					if (!reliefTypeSelect) return;
					
					const reliefTypeId = reliefTypeSelect.value;
					const reliefTypeDisplay = document.getElementById('relief-type-display');
					const unitDisplay = document.getElementById('unit-display');
					
					if (reliefTypeId && this.reliefTypes[reliefTypeId]) {
						const selectedOption = reliefTypeSelect.options[reliefTypeSelect.selectedIndex];
						const reliefTypeName = selectedOption.textContent;
						const unit = this.reliefTypes[reliefTypeId].unit_bn || this.reliefTypes[reliefTypeId].unit;
						
						if (reliefTypeDisplay) {
							reliefTypeDisplay.textContent = reliefTypeName;
						}
						if (unitDisplay) {
							unitDisplay.textContent = unit;
						}
					} else {
						if (reliefTypeDisplay) {
							reliefTypeDisplay.textContent = '{{ __('this relief type') }}';
						}
						if (unitDisplay) {
							unitDisplay.textContent = '{{ __('Unit') }}';
						}
					}
				},
				
				init() {
					// Initialize allocated amount display
					const allocatedAmountInput = document.getElementById('allocated_amount');
					if (allocatedAmountInput && allocatedAmountInput.value) {
						// Convert to Bangla for display if it contains English numbers
						const englishValue = this.banglaToEnNumber(allocatedAmountInput.value);
						if (englishValue !== allocatedAmountInput.value) {
							// Input contains Bangla, ensure hidden field has English
							const hiddenInput = document.getElementById('allocated_amount_en');
							if (hiddenInput) {
								hiddenInput.value = englishValue;
							}
						}
						this.allocatedAmountDisplay = allocatedAmountInput.value;
					}
					
					// Update relief type display on page load
					this.updateReliefType();
					
					// Listen for relief type changes
					const reliefTypeSelect = document.getElementById('relief_type_id');
					if (reliefTypeSelect) {
						reliefTypeSelect.addEventListener('change', () => this.updateReliefType());
					}
				}
			}
		}
	</script>
</x-main-layout>
