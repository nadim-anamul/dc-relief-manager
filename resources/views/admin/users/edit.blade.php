<x-main-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit User: ') . $user->name }}
            </h2>
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('Back to Users') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Full Name') }} *
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $user->name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Email Address') }} *
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email', $user->email) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Phone Number') }} *
                                </label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone', $user->phone) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <!-- Approval Status -->
                            <div>
                                <label for="is_approved" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Approval Status') }}
                                </label>
                                <select name="is_approved" 
                                        id="is_approved"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="1" {{ old('is_approved', $user->is_approved) ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                    <option value="0" {{ !old('is_approved', $user->is_approved) ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                </select>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('New Password') }} ({{ __('leave blank to keep current') }})
                                </label>
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Confirm New Password') }}
                                </label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                {{ __('Roles') }} *
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($roles as $role)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               name="roles[]" 
                                               value="{{ $role->name }}" 
                                               id="role_{{ $role->id }}"
                                               {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label for="role_{{ $role->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('Update User') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
