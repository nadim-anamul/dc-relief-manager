<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.relief-types.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Relief Type</h1>
		</div>
	</x-slot>

	<div class="max-w-2xl mx-auto">
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Relief Type Information</h3>
			</div>
			<div class="p-6">
				<form action="{{ route('admin.relief-types.update', $reliefType) }}" method="POST" class="space-y-6">
					@csrf
					@method('PUT')

					<!-- Name -->
					<div>
						<label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Name <span class="text-red-500">*</span>
						</label>
						<input type="text" 
							name="name" 
							id="name" 
							value="{{ old('name', $reliefType->name) }}"
							class="input-field @error('name') border-red-500 dark:border-red-400 @enderror"
							placeholder="e.g., Rice, Dal, Oil"
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
							value="{{ old('name_bn', $reliefType->name_bn) }}"
							class="input-field @error('name_bn') border-red-500 dark:border-red-400 @enderror"
							placeholder="e.g., চাল, ডাল, তেল">
						@error('name_bn')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Description -->
					<div>
						<label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Description
						</label>
						<textarea name="description" 
							id="description" 
							rows="3"
							class="input-field @error('description') border-red-500 dark:border-red-400 @enderror"
							placeholder="Enter description">{{ old('description', $reliefType->description) }}</textarea>
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
							placeholder="বাংলা বর্ণনা লিখুন">{{ old('description_bn', $reliefType->description_bn) }}</textarea>
						@error('description_bn')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Unit -->
					<div>
						<label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Unit
						</label>
						<input type="text" 
							name="unit" 
							id="unit" 
							value="{{ old('unit', $reliefType->unit) }}"
							class="input-field @error('unit') border-red-500 dark:border-red-400 @enderror"
							placeholder="e.g., kg, liter, piece">
						@error('unit')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Bengali Unit -->
					<div>
						<label for="unit_bn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Bengali Unit
						</label>
						<input type="text" 
							name="unit_bn" 
							id="unit_bn" 
							value="{{ old('unit_bn', $reliefType->unit_bn) }}"
							class="input-field @error('unit_bn') border-red-500 dark:border-red-400 @enderror"
							placeholder="e.g., কেজি, লিটার, টি">
						@error('unit_bn')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Color Code -->
					<div>
						<label for="color_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Color Code
						</label>
						<div class="flex items-center space-x-3">
							<input type="color" 
								name="color_code" 
								id="color_code" 
								value="{{ old('color_code', $reliefType->color_code ?? '#3B82F6') }}"
								class="h-10 w-16 rounded border border-gray-300 dark:border-gray-600">
							<input type="text" 
								name="color_code_text" 
								id="color_code_text" 
								value="{{ old('color_code', $reliefType->color_code ?? '#3B82F6') }}"
								class="input-field @error('color_code') border-red-500 dark:border-red-400 @enderror"
								placeholder="#3B82F6"
								pattern="^#[0-9A-Fa-f]{6}$">
						</div>
						<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Choose a color for UI display (hex format)</p>
						@error('color_code')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Sort Order -->
					<div>
						<label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
							Sort Order
						</label>
						<input type="number" 
							name="sort_order" 
							id="sort_order" 
							value="{{ old('sort_order', $reliefType->sort_order) }}"
							class="input-field @error('sort_order') border-red-500 dark:border-red-400 @enderror"
							placeholder="0"
							min="0">
						<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Lower numbers appear first in lists</p>
						@error('sort_order')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Status -->
					<div>
						<label class="flex items-center">
							<input type="checkbox" 
								name="is_active" 
								value="1"
								{{ old('is_active', $reliefType->is_active) ? 'checked' : '' }}
								class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
							<span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
						</label>
					</div>

					<!-- Submit Buttons -->
					<div class="flex justify-end space-x-3">
						<a href="{{ route('admin.relief-types.index') }}" class="btn-secondary">
							Cancel
						</a>
						<button type="submit" class="btn-primary">
							Update Relief Type
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		// Sync color picker with text input
		document.getElementById('color_code').addEventListener('input', function() {
			document.getElementById('color_code_text').value = this.value;
		});

		document.getElementById('color_code_text').addEventListener('input', function() {
			if (this.value.match(/^#[0-9A-Fa-f]{6}$/)) {
				document.getElementById('color_code').value = this.value;
			}
		});
	</script>
</x-main-layout>
