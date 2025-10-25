<x-main-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('নতুন ব্যবহারকারী তৈরি করুন') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('ব্যবহারকারীদের তালিকায় ফিরে যান') }}
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

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('পূর্ণ নাম') }} *
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('ইমেইল ঠিকানা') }} *
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('ফোন নম্বর') }} *
                                </label>
                                <input type="text" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone') }}"
                                       placeholder="{{ __('০১XXXXXXXXX') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('বাংলাদেশি মোবাইল নম্বর (যেমন, ০১৭১২৩৪৫৬৭৮)') }}
                                </p>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('পাসওয়ার্ড') }} *
                                </label>
                                <input type="password" 
                                       name="password" 
                                       id="password"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <!-- Password Confirmation -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('পাসওয়ার্ড নিশ্চিত করুন') }} *
                                </label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <!-- Organization Type -->
                            <div>
                                <label for="organization_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('সংস্থার ধরন') }}
                                </label>
                                <select name="organization_type_id" 
                                        id="organization_type_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">{{ __('সংস্থার ধরন নির্বাচন করুন') }}</option>
                                    @foreach($organizationTypes as $orgType)
                                        <option value="{{ $orgType->id }}" {{ old('organization_type_id') == $orgType->id ? 'selected' : '' }}>
                                            {{ $orgType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Approval Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    {{ __('অনুমোদনের অবস্থা') }}
                                </label>
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="is_approved" 
                                           value="1" 
                                           id="is_approved"
                                           {{ old('is_approved', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_approved" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        {{ __('ব্যবহারকারী অনুমোদিত (তৎক্ষণাৎ লগইন করতে পারবে)') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Roles -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                {{ __('ভূমিকা') }} *
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($roles as $role)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               name="roles[]" 
                                               value="{{ $role->name }}" 
                                               id="role_{{ $role->id }}"
                                               {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('ব্যবহারকারী তৈরি করুন') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
