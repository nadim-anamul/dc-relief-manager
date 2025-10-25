<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-6">
            <div>
                <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Current Password') }}
                </label>
                <input id="update_password_current_password" name="current_password" type="password" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('updatePassword.current_password') border-red-500 @enderror" 
                       autocomplete="current-password" 
                       placeholder="{{ __('Enter your current password') }}">
                @error('updatePassword.current_password')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="update_password_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('New Password') }}
                    </label>
                    <input id="update_password_password" name="password" type="password" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('updatePassword.password') border-red-500 @enderror" 
                           autocomplete="new-password" 
                           placeholder="{{ __('Enter your new password') }}">
                    @error('updatePassword.password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Confirm New Password') }}
                    </label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('updatePassword.password_confirmation') border-red-500 @enderror" 
                           autocomplete="new-password" 
                           placeholder="{{ __('Confirm your new password') }}">
                    @error('updatePassword.password_confirmation')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                @if (session('status') === 'password-updated')
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="flex items-center text-green-600 dark:text-green-400"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ __('Password updated successfully!') }}</span>
                    </div>
                @endif
            </div>
            
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                {{ __('Update Password') }}
            </button>
        </div>
    </form>
</section>
