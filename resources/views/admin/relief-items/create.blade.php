<x-main-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Create Relief Item') }}</h1>
            <a href="{{ route('admin.relief-items.index') }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Back to Relief Items') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card p-6">
            <form method="POST" action="{{ route('admin.relief-items.store') }}">
                @csrf
                
                <div class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Item Name *') }}</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="form-input @error('name') border-red-500 @enderror"
                               placeholder="{{ __('e.g., Rice, Cash Relief, Medicine') }}"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bengali Name -->
                    <div>
                        <label for="name_bn" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Bengali Name') }}</label>
                        <input type="text" 
                               id="name_bn" 
                               name="name_bn" 
                               value="{{ old('name_bn') }}"
                               class="form-input @error('name_bn') border-red-500 @enderror"
                               placeholder="e.g., চাল, নগদ ত্রাণ, ঔষধ">
                        @error('name_bn')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Item Type *') }}</label>
                        <select id="type" 
                                name="type" 
                                class="form-select @error('type') border-red-500 @enderror"
                                required>
                            <option value="">{{ __('Select Type') }}</option>
                            <option value="monetary" {{ old('type') === 'monetary' ? 'selected' : '' }}>{{ __('Monetary') }}</option>
                            <option value="food" {{ old('type') === 'food' ? 'selected' : '' }}>{{ __('Food') }}</option>
                            <option value="medical" {{ old('type') === 'medical' ? 'selected' : '' }}>{{ __('Medical') }}</option>
                            <option value="shelter" {{ old('type') === 'shelter' ? 'selected' : '' }}>{{ __('Shelter') }}</option>
                            <option value="other" {{ old('type') === 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Unit *') }}</label>
                        <select id="unit" 
                                name="unit" 
                                class="form-select @error('unit') border-red-500 @enderror"
                                required>
                            <option value="">{{ __('Select Unit') }}</option>
                            <option value="BDT" {{ old('unit') === 'BDT' ? 'selected' : '' }}>{{ __('BDT (৳)') }}</option>
                            <option value="metric_ton" {{ old('unit') === 'metric_ton' ? 'selected' : '' }}>{{ __('Metric Ton') }}</option>
                            <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>{{ __('Kilogram (Kg)') }}</option>
                            <option value="liter" {{ old('unit') === 'liter' ? 'selected' : '' }}>{{ __('Liter') }}</option>
                            <option value="piece" {{ old('unit') === 'piece' ? 'selected' : '' }}>{{ __('Piece') }}</option>
                            <option value="box" {{ old('unit') === 'box' ? 'selected' : '' }}>{{ __('Box') }}</option>
                        </select>
                        @error('unit')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Description') }}</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="form-textarea @error('description') border-red-500 @enderror"
                                  placeholder="{{ __('Brief description of the relief item') }}">{{ old('description') }}</textarea>
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
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ __('Active') }}</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Uncheck to deactivate this relief item') }}</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.relief-items.index') }}" class="btn-secondary">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('Create Relief Item') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-main-layout>
