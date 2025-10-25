<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Full Name') }}
                </label>
                <input id="name" name="name" type="text" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('name') border-red-500 @enderror" 
                       value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" 
                       placeholder="{{ __('Enter your full name') }}">
                @error('name')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Email Address') }}
                </label>
                <input id="email" name="email" type="email" 
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('email') border-red-500 @enderror" 
                       value="{{ old('email', $user->email) }}" required autocomplete="username" 
                       placeholder="{{ __('Enter your email address') }}">
                @error('email')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                {{ __('Phone Number') }}
            </label>
            <input id="phone" name="phone" type="tel" 
                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('phone') border-red-500 @enderror" 
                   value="{{ old('phone', $user->phone) }}" required autocomplete="tel" 
                   placeholder="{{ __('Enter your phone number') }}">
            @error('phone')
                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                @if (session('status') === 'profile-updated')
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
                        <span class="text-sm font-medium">{{ __('Profile updated successfully!') }}</span>
                    </div>
                @endif
            </div>
            
            <button type="submit" 
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ __('Save Changes') }}
            </button>
        </div>
    </form>
</section>
