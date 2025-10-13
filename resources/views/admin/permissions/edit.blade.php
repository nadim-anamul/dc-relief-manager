<x-main-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.permissions.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Edit Permission') }}</h1>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Edit Permission Information') }}</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.permissions.update', $permission) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Permission Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Permission Name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $permission->name) }}"
                               class="input-field @error('name') border-red-500 dark:border-red-400 @enderror"
                               placeholder="{{ __('e.g., users.manage, reports.view') }}"
                               required>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ __('Use lowercase letters, dots, and hyphens (e.g., users.manage)') }}
                        </p>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guard Name -->
                    <div>
                        <label for="guard_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Guard Name') }} <span class="text-red-500">*</span>
                        </label>
                        <select name="guard_name" 
                                id="guard_name" 
                                class="input-field @error('guard_name') border-red-500 dark:border-red-400 @enderror"
                                required>
                            <option value="web" {{ old('guard_name', $permission->guard_name) == 'web' ? 'selected' : '' }}>{{ __('Web') }}</option>
                            <option value="api" {{ old('guard_name', $permission->guard_name) == 'api' ? 'selected' : '' }}>{{ __('API') }}</option>
                        </select>
                        @error('guard_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Permission Type Info -->
                    @if(str_starts_with($permission->name, 'projects.'))
                        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                        {{ __('Project-Based Permission') }}
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                        <p>{{ __('This is a project-based permission. Be careful when editing the name as it may affect project-specific access controls.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Assigned Roles Info -->
                    @if($permission->roles->count() > 0)
                        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                        {{ __('Assigned to Roles') }}
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                        <p>{{ __('This permission is assigned to') }} {{ $permission->roles->count() }} {{ __('role(s)') }}. 
                                           {{ __('Editing this permission may affect user access.') }}</p>
                                        <div class="mt-2">
                                            @foreach($permission->roles as $role)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 mr-2 mb-1">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.permissions.show', $permission) }}" class="btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ __('Update Permission') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-main-layout>
