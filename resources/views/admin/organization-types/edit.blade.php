<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.organization-types.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Organization Type: {{ $organizationType->name }}</h1>
		</div>
	</x-slot>

	<div class="max-w-2xl mx-auto">
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Organization Type Information') }}</h3>
			</div>
			<div class="p-6">
				<form action="{{ route('admin.organization-types.update', $organizationType) }}" method="POST" class="space-y-6">
					@csrf
					@method('PUT')

					<!-- Organization Type Name -->
					<div>
					<label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						{{ __('Name') }} <span class="text-red-500">*</span>
					</label>
						<input type="text" 
							name="name" 
							id="name" 
							value="{{ old('name', $organizationType->name) }}"
							class="input-field @error('name') border-red-500 dark:border-red-400 @enderror"
						placeholder="{{ __('Enter organization type name (e.g., NGO, Government, Private)') }}"
							required>
						@error('name')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Description -->
					<div>
					<label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
						{{ __('Description') }}
					</label>
						<textarea name="description" 
							id="description" 
							rows="4"
							class="input-field @error('description') border-red-500 dark:border-red-400 @enderror"
						placeholder="{{ __('Enter description of the organization type') }}">{{ old('description', $organizationType->description) }}</textarea>
						@error('description')
							<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
						@enderror
					</div>

					<!-- Submit Buttons -->
				<div class="flex justify-end space-x-3">
					<a href="{{ route('admin.organization-types.show', $organizationType) }}" class="btn-secondary">
						{{ __('Cancel') }}
					</a>
					<button type="submit" class="btn-primary">
						{{ __('Update Organization Type') }}
					</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</x-main-layout>
