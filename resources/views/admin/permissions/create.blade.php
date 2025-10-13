<x-main-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.permissions.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Create New Permission') }}</h1>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Permission Information') }}</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.permissions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Permission Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            {{ __('Permission Type') }} <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" 
                                       name="permission_type" 
                                       value="general" 
                                       id="permission_type_general"
                                       class="sr-only"
                                       {{ old('permission_type', 'general') == 'general' ? 'checked' : '' }}
                                       onchange="togglePermissionType()">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-4 h-4 border-2 border-gray-300 dark:border-gray-600 rounded-full relative">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden" id="general_indicator"></div>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('General Permission') }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Create a general system permission') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" 
                                       name="permission_type" 
                                       value="project" 
                                       id="permission_type_project"
                                       class="sr-only"
                                       {{ old('permission_type') == 'project' ? 'checked' : '' }}
                                       onchange="togglePermissionType()">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-4 h-4 border-2 border-gray-300 dark:border-gray-600 rounded-full relative">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden" id="project_indicator"></div>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Project-Based Permission') }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Create permissions for specific projects') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('permission_type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- General Permission Fields -->
                    <div id="general_permission_fields">
                        <!-- Permission Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Permission Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
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
                                <option value="web" {{ old('guard_name', 'web') == 'web' ? 'selected' : '' }}>{{ __('Web') }}</option>
                                <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>{{ __('API') }}</option>
                            </select>
                            @error('guard_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Project-Based Permission Fields -->
                    <div id="project_permission_fields" class="hidden">
                        <!-- Project Selection -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Select Project') }} <span class="text-red-500">*</span>
                            </label>
                            <select name="project_id" 
                                    id="project_id" 
                                    class="input-field @error('project_id') border-red-500 dark:border-red-400 @enderror"
                                    onchange="updatePermissionName()">
                                <option value="">{{ __('Select a Project') }}</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }} 
                                        @if($project->economicYear?->is_current) - {{ __('Current') }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                {{ __('Permission Actions') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach(['view', 'create', 'edit', 'delete', 'approve', 'reject', 'manage'] as $action)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               name="actions[]" 
                                               value="{{ $action }}" 
                                               id="action_{{ $action }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                               {{ in_array($action, old('actions', [])) ? 'checked' : '' }}
                                               onchange="updatePermissionName()">
                                        <label for="action_{{ $action }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            {{ ucfirst($action) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('actions')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Generated Permission Names -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('Generated Permissions') }}
                            </label>
                            <div id="generated_permissions" class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md p-4 min-h-[100px]">
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Select a project and actions to see generated permissions') }}</p>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('These permissions will be created automatically') }}
                            </p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.permissions.index') }}" class="btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ __('Create Permission(s)') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Projects data for permission generation
        const projects = {
            @foreach($projects as $project)
                {{ $project->id }}: {
                    name: '{{ addslashes($project->name) }}',
                    slug: '{{ preg_match('/^[a-zA-Z0-9\s\-_]+$/', $project->name) ? strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $project->name), '-')) : 'project-' . $project->id }}'
                },
            @endforeach
        };

        function togglePermissionType() {
            const generalType = document.getElementById('permission_type_general');
            const projectType = document.getElementById('permission_type_project');
            const generalFields = document.getElementById('general_permission_fields');
            const projectFields = document.getElementById('project_permission_fields');
            const generalIndicator = document.getElementById('general_indicator');
            const projectIndicator = document.getElementById('project_indicator');

            if (generalType.checked) {
                generalFields.classList.remove('hidden');
                projectFields.classList.add('hidden');
                generalIndicator.classList.remove('hidden');
                projectIndicator.classList.add('hidden');
                
                // Make general fields required
                document.getElementById('name').required = true;
                document.getElementById('guard_name').required = true;
                document.getElementById('project_id').required = false;
            } else if (projectType.checked) {
                generalFields.classList.add('hidden');
                projectFields.classList.remove('hidden');
                generalIndicator.classList.add('hidden');
                projectIndicator.classList.remove('hidden');
                
                // Make project fields required
                document.getElementById('name').required = false;
                document.getElementById('guard_name').required = false;
                document.getElementById('project_id').required = true;
                
                updatePermissionName();
            }
        }

        function updatePermissionName() {
            const projectId = document.getElementById('project_id').value;
            const actions = Array.from(document.querySelectorAll('input[name="actions[]"]:checked')).map(cb => cb.value);
            const generatedDiv = document.getElementById('generated_permissions');

            if (!projectId || actions.length === 0) {
                generatedDiv.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">{{ __("Select a project and actions to see generated permissions") }}</p>';
                return;
            }

            const project = projects[projectId];
            if (!project) return;

            const permissions = actions.map(action => {
                return `projects.${project.slug}.${action}`;
            });

            let html = '<div class="space-y-2">';
            permissions.forEach(permission => {
                html += `<div class="flex items-center justify-between p-2 bg-white dark:bg-gray-600 border border-gray-200 dark:border-gray-500 rounded">
                    <span class="text-sm font-mono text-gray-900 dark:text-white">${permission}</span>
                    <input type="hidden" name="generated_permissions[]" value="${permission}">
                </div>`;
            });
            html += '</div>';

            generatedDiv.innerHTML = html;
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePermissionType();
            
            // Update radio button indicators
            const generalType = document.getElementById('permission_type_general');
            const projectType = document.getElementById('permission_type_project');
            const generalIndicator = document.getElementById('general_indicator');
            const projectIndicator = document.getElementById('project_indicator');

            generalType.addEventListener('change', function() {
                if (this.checked) {
                    generalIndicator.classList.remove('hidden');
                    projectIndicator.classList.add('hidden');
                }
            });

            projectType.addEventListener('change', function() {
                if (this.checked) {
                    projectIndicator.classList.remove('hidden');
                    generalIndicator.classList.add('hidden');
                }
            });
        });
    </script>
</x-main-layout>
