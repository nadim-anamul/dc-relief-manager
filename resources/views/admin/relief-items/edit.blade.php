<x-main-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Relief Item</h1>
            <a href="{{ route('admin.relief-items.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Relief Items
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card p-6">
            <form method="POST" action="{{ route('admin.relief-items.update', $reliefItem) }}">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Item Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $reliefItem->name) }}"
                               class="form-input @error('name') border-red-500 @enderror"
                               placeholder="e.g., Rice, Cash Relief, Medicine"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bengali Name -->
                    <div>
                        <label for="name_bn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bengali Name</label>
                        <input type="text" 
                               id="name_bn" 
                               name="name_bn" 
                               value="{{ old('name_bn', $reliefItem->name_bn) }}"
                               class="form-input @error('name_bn') border-red-500 @enderror"
                               placeholder="e.g., চাল, নগদ ত্রাণ, ঔষধ">
                        @error('name_bn')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Item Type *</label>
                        <select id="type" 
                                name="type" 
                                class="form-select @error('type') border-red-500 @enderror"
                                required>
                            <option value="">Select Type</option>
                            <option value="monetary" {{ old('type', $reliefItem->type) === 'monetary' ? 'selected' : '' }}>Monetary</option>
                            <option value="food" {{ old('type', $reliefItem->type) === 'food' ? 'selected' : '' }}>Food</option>
                            <option value="medical" {{ old('type', $reliefItem->type) === 'medical' ? 'selected' : '' }}>Medical</option>
                            <option value="shelter" {{ old('type', $reliefItem->type) === 'shelter' ? 'selected' : '' }}>Shelter</option>
                            <option value="other" {{ old('type', $reliefItem->type) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit *</label>
                        <select id="unit" 
                                name="unit" 
                                class="form-select @error('unit') border-red-500 @enderror"
                                required>
                            <option value="">Select Unit</option>
                            <option value="BDT" {{ old('unit', $reliefItem->unit) === 'BDT' ? 'selected' : '' }}>BDT (৳)</option>
                            <option value="metric_ton" {{ old('unit', $reliefItem->unit) === 'metric_ton' ? 'selected' : '' }}>Metric Ton</option>
                            <option value="kg" {{ old('unit', $reliefItem->unit) === 'kg' ? 'selected' : '' }}>Kilogram (Kg)</option>
                            <option value="liter" {{ old('unit', $reliefItem->unit) === 'liter' ? 'selected' : '' }}>Liter</option>
                            <option value="piece" {{ old('unit', $reliefItem->unit) === 'piece' ? 'selected' : '' }}>Piece</option>
                            <option value="box" {{ old('unit', $reliefItem->unit) === 'box' ? 'selected' : '' }}>Box</option>
                        </select>
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="form-textarea @error('description') border-red-500 @enderror"
                                  placeholder="Brief description of the relief item">{{ old('description', $reliefItem->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $reliefItem->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Uncheck to deactivate this relief item</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.relief-items.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Relief Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-main-layout>
