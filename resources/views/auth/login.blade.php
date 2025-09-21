<x-guest-layout>
	<!-- Session Status -->
	<x-auth-session-status class="mb-4" :status="session('status')" />

	<form method="POST" action="{{ route('login') }}" class="space-y-6">
		@csrf

		<div class="text-center mb-6">
			<h2 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back</h2>
			<p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Sign in to your account to continue</p>
		</div>

		<!-- Email Address -->
		<div>
			<x-input-label for="email" :value="__('Email Address')" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" />
			<x-text-input id="email" 
				class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
				type="email" 
				name="email" 
				:value="old('email')" 
				required 
				autofocus 
				autocomplete="username" 
				placeholder="Enter your email" />
			<x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
		</div>

		<!-- Password -->
		<div>
			<x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" />
			<x-text-input id="password" 
				class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
				type="password"
				name="password"
				required 
				autocomplete="current-password" 
				placeholder="Enter your password" />
			<x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
		</div>

		<!-- Remember Me & Forgot Password -->
		<div class="flex items-center justify-between">
			<div class="flex items-center">
				<input id="remember_me" 
					type="checkbox" 
					class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700" 
					name="remember">
				<label for="remember_me" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
					{{ __('Remember me') }}
				</label>
			</div>

			@if (Route::has('password.request'))
				<a class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 font-medium" href="{{ route('password.request') }}">
					{{ __('Forgot your password?') }}
				</a>
			@endif
		</div>

		<!-- Submit Button -->
		<div>
			<x-primary-button class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
				{{ __('Sign in') }}
			</x-primary-button>
		</div>

		<!-- Register Link -->
		<div class="text-center">
			<p class="text-sm text-gray-600 dark:text-gray-400">
				Don't have an account? 
				<a href="{{ route('register') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
					{{ __('Sign up') }}
				</a>
			</p>
		</div>
	</form>
</x-guest-layout>