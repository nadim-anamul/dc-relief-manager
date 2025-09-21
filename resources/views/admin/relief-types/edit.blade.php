<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center justify-between">
			<div class="flex items-center">
				<a href="{{ route('admin.relief-types.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200">
					<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
					</svg>
				</a>
				<div>
					<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Relief Type</h1>
					<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update relief type: {{ $reliefType->display_name }}</p>
				</div>
			</div>
			<div class="flex space-x-3">
				<a href="{{ route('admin.relief-types.show', $reliefType) }}" class="btn-secondary">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
					</svg>
					View
				</a>
				<a href="{{ route('admin.relief-types.index') }}" class="btn-secondary">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
					</svg>
					Cancel
				</a>
			</div>
		</div>
	</x-slot>

	<div class="max-w-4xl mx-auto space-y-6">
		<form id="relief-type-form" action="{{ route('admin.relief-types.update', $reliefType) }}" method="POST" class="space-y-6">
			@csrf
			@method('PUT')

			<!-- Basic Information Section -->
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<div class="flex items-center">
						<div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full mr-3">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-medium text-gray-900 dark:text-white">Basic Information</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">Update the basic details for the relief type</p>
						</div>
					</div>
				</div>
				<div class="p-6 space-y-6">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<!-- English Name -->
						<div>
							<label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								English Name <span class="text-red-500">*</span>
							</label>
							<input type="text" 
								name="name" 
								id="name" 
								value="{{ old('name', $reliefType->name) }}"
								class="input-field @error('name') border-red-500 dark:border-red-400 @enderror"
								placeholder="e.g., Rice, Wheat, Cash"
								required>
							@error('name')
								<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
							@enderror
						</div>

						<!-- Bengali Name -->
						<div>
							<label for="name_bn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								Bengali Name <span class="text-red-500">*</span>
							</label>
							<input type="text" 
								name="name_bn" 
								id="name_bn" 
								value="{{ old('name_bn', $reliefType->name_bn) }}"
								class="input-field @error('name_bn') border-red-500 dark:border-red-400 @enderror"
								placeholder="e.g., চাল, গম, টাকা"
								required>
							@error('name_bn')
								<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
							@enderror
						</div>
					</div>

					<!-- Description -->
					<div>
						<label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							English Description
						</label>
						<textarea name="description" 
							id="description" 
							rows="3"
							class="input-field @error('description') border-red-500 dark:border-red-400 @enderror"
							placeholder="Describe this relief type and its purpose">{{ old('description', $reliefType->description) }}</textarea>
						@error('description')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Bengali Description -->
					<div>
						<label for="description_bn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Bengali Description
						</label>
						<textarea name="description_bn" 
							id="description_bn" 
							rows="3"
							class="input-field @error('description_bn') border-red-500 dark:border-red-400 @enderror"
							placeholder="এই ত্রাণের ধরন এবং এর উদ্দেশ্য বর্ণনা করুন">{{ old('description_bn', $reliefType->description_bn) }}</textarea>
						@error('description_bn')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>
				</div>
			</div>

			<!-- Unit & Display Settings Section -->
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<div class="flex items-center">
						<div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full mr-3">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-medium text-gray-900 dark:text-white">Unit & Display Settings</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">Configure measurement units and visual appearance</p>
						</div>
					</div>
				</div>
				<div class="p-6 space-y-6">
					<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
						<!-- Unit -->
						<div>
							<label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								Unit <span class="text-red-500">*</span>
							</label>
							<select name="unit" 
								id="unit" 
								class="input-field @error('unit') border-red-500 dark:border-red-400 @enderror"
								required>
								<option value="">Select a unit</option>
								<option value="Taka" {{ old('unit', $reliefType->unit) == 'Taka' ? 'selected' : '' }}>Taka (৳)</option>
								<option value="Metric Ton" {{ old('unit', $reliefType->unit) == 'Metric Ton' ? 'selected' : '' }}>Metric Ton</option>
								<option value="Kg" {{ old('unit', $reliefType->unit) == 'Kg' ? 'selected' : '' }}>Kilogram</option>
								<option value="Liter" {{ old('unit', $reliefType->unit) == 'Liter' ? 'selected' : '' }}>Liter</option>
								<option value="Piece" {{ old('unit', $reliefType->unit) == 'Piece' ? 'selected' : '' }}>Piece</option>
								<option value="Bundle" {{ old('unit', $reliefType->unit) == 'Bundle' ? 'selected' : '' }}>Bundle</option>
								<option value="Box" {{ old('unit', $reliefType->unit) == 'Box' ? 'selected' : '' }}>Box</option>
								<option value="Set" {{ old('unit', $reliefType->unit) == 'Set' ? 'selected' : '' }}>Set</option>
							</select>
							@error('unit')
								<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
							@enderror
						</div>

						<!-- Bengali Unit -->
						<div>
							<label for="unit_bn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
								Bengali Unit <span class="text-red-500">*</span>
							</label>
							<select name="unit_bn" 
								id="unit_bn" 
								class="input-field @error('unit_bn') border-red-500 dark:border-red-400 @enderror"
								required>
								<option value="">একক নির্বাচন করুন</option>
								<option value="টাকা" {{ old('unit_bn', $reliefType->unit_bn) == 'টাকা' ? 'selected' : '' }}>টাকা</option>
								<option value="মেট্রিক টন" {{ old('unit_bn', $reliefType->unit_bn) == 'মেট্রিক টন' ? 'selected' : '' }}>মেট্রিক টন</option>
								<option value="কেজি" {{ old('unit_bn', $reliefType->unit_bn) == 'কেজি' ? 'selected' : '' }}>কেজি</option>
								<option value="লিটার" {{ old('unit_bn', $reliefType->unit_bn) == 'লিটার' ? 'selected' : '' }}>লিটার</option>
								<option value="টি" {{ old('unit_bn', $reliefType->unit_bn) == 'টি' ? 'selected' : '' }}>টি</option>
								<option value="বান্ডিল" {{ old('unit_bn', $reliefType->unit_bn) == 'বান্ডিল' ? 'selected' : '' }}>বান্ডিল</option>
								<option value="বক্স" {{ old('unit_bn', $reliefType->unit_bn) == 'বক্স' ? 'selected' : '' }}>বক্স</option>
								<option value="সেট" {{ old('unit_bn', $reliefType->unit_bn) == 'সেট' ? 'selected' : '' }}>সেট</option>
							</select>
							@error('unit_bn')
								<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
							@enderror
						</div>
					</div>


					<!-- Sort Order -->
					<div>
						<label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Display Order
						</label>
						<input type="number" 
							name="sort_order" 
							id="sort_order" 
							value="{{ old('sort_order', $reliefType->sort_order) }}"
							class="input-field @error('sort_order') border-red-500 dark:border-red-400 @enderror"
							placeholder="0"
							min="0">
						@error('sort_order')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
						<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Lower numbers appear first in lists (0 = highest priority)</p>
					</div>

					<!-- Status -->
					<div class="flex items-start">
						<div class="flex items-center h-5">
							<input type="checkbox" 
								name="is_active" 
								id="is_active"
								value="1"
								{{ old('is_active', $reliefType->is_active) ? 'checked' : '' }}
								class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
						</div>
						<div class="ml-3 text-sm">
							<label for="is_active" class="font-medium text-gray-700 dark:text-gray-300">
								Active Status
							</label>
							<p class="text-gray-500 dark:text-gray-400">Enable this relief type for use in projects and applications</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Preview Section -->
			<div class="card">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
					<div class="flex items-center">
						<div class="flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full mr-3">
							<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-medium text-gray-900 dark:text-white">Preview</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">See how your relief type will appear</p>
						</div>
					</div>
				</div>
				<div class="p-6">
					<div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between">
							<div>
								<h4 class="font-medium text-gray-900 dark:text-white" id="preview-name">{{ $reliefType->name }}</h4>
								<p class="text-sm text-gray-500 dark:text-gray-400" id="preview-name-bn">{{ $reliefType->name_bn }}</p>
							</div>
							<div class="text-right">
								<p class="text-sm font-medium text-gray-900 dark:text-white">Unit: <span id="preview-unit">{{ $reliefType->unit }}</span></p>
								<p class="text-xs text-gray-500 dark:text-gray-400">(<span id="preview-unit-bn">{{ $reliefType->unit_bn }}</span>)</p>
							</div>
						</div>
						<div class="mt-3">
							<p class="text-sm text-gray-600 dark:text-gray-400" id="preview-description">{{ $reliefType->description ?: 'No description provided' }}</p>
						</div>
					</div>
				</div>
			</div>

			<!-- Submit Buttons -->
			<div class="flex justify-end space-x-3 pt-6">
				<a href="{{ route('admin.relief-types.index') }}" class="btn-secondary">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
					</svg>
					Cancel
				</a>
				<button type="submit" class="btn-primary">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
					</svg>
					Update Relief Type
				</button>
			</div>
		</form>
	</div>

	<script>
		// Unit mapping for auto-selection
		const unitMapping = {
			'Taka': 'টাকা',
			'Metric Ton': 'মেট্রিক টন',
			'Kg': 'কেজি',
			'Liter': 'লিটার',
			'Piece': 'টি',
			'Bundle': 'বান্ডিল',
			'Box': 'বক্স',
			'Set': 'সেট'
		};

		// Auto-sync unit selections
		document.getElementById('unit').addEventListener('change', function() {
			const unitBnSelect = document.getElementById('unit_bn');
			const englishUnit = this.value;
			if (unitMapping[englishUnit]) {
				unitBnSelect.value = unitMapping[englishUnit];
			}
			updatePreview();
		});

		document.getElementById('unit_bn').addEventListener('change', updatePreview);

		// Update preview in real-time
		function updatePreview() {
			const name = document.getElementById('name').value || 'Relief Type Name';
			const nameBn = document.getElementById('name_bn').value || 'ত্রাণের ধরন';
			const unit = document.getElementById('unit').value || 'Unit';
			const unitBn = document.getElementById('unit_bn').value || 'একক';
			const description = document.getElementById('description').value || 'No description provided';
			document.getElementById('preview-name').textContent = name;
			document.getElementById('preview-name-bn').textContent = nameBn;
			document.getElementById('preview-unit').textContent = unit;
			document.getElementById('preview-unit-bn').textContent = unitBn;
			document.getElementById('preview-description').textContent = description;
		}

		// Add event listeners for real-time preview
		['name', 'name_bn', 'description'].forEach(id => {
			document.getElementById(id).addEventListener('input', updatePreview);
		});


		// Initialize preview
		updatePreview();
	</script>
</x-main-layout>