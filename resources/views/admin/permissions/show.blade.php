<x-main-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.permissions.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Permission Details') }}</h1>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Permission Information') }}</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Permission Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Permission Name') }}
                        </label>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md font-mono">
                            {{ $permission->name }}
                        </div>
                    </div>

                    <!-- Guard Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Guard Name') }}
                        </label>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            {{ $permission->guard_name }}
                        </div>
                    </div>

                    <!-- Created Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Created Date') }}
                        </label>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            {{ $permission->created_at->format('M d, Y H:i:s') }}
                        </div>
                    </div>

                    <!-- Updated Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ __('Last Updated') }}
                        </label>
                        <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            {{ $permission->updated_at->format('M d, Y H:i:s') }}
                        </div>
                    </div>
                </div>

                <!-- Permission Type -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Permission Type') }}
                    </label>
                    <div class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                        @if(str_starts_with($permission->name, 'projects.'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ __('Project-Based Permission') }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ __('General Permission') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Assigned Roles -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        {{ __('Assigned Roles') }} ({{ $permission->roles->count() }})
                    </label>
                    @if($permission->roles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($permission->roles as $role)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $role->name }}</span>
                                    <a href="{{ route('admin.roles.show', $role) }}" 
                                       class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ __('View Role') }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                            {{ __('This permission is not assigned to any roles.') }}
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.permissions.index') }}" class="btn-secondary">
                        {{ __('Back to Permissions') }}
                    </a>
                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn-primary">
                        {{ __('Edit Permission') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
