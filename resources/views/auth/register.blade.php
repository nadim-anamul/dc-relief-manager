<x-guest-layout>
	<form method="POST" action="{{ route('register') }}" class="space-y-6">
		@csrf

		<div class="text-center mb-6">
			<h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create your account</h2>
			<p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Join DC Relief Management System</p>
		</div>

		<!-- Name -->
		<div>
			<x-input-label for="name" :value="__('Full Name')" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" />
			<x-text-input id="name" 
				class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
				type="text" 
				name="name" 
				:value="old('name')" 
				required 
				autofocus 
				autocomplete="name" 
				placeholder="Enter your full name" />
			<x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
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
				autocomplete="username" 
				placeholder="Enter your email address" />
			<x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
		</div>

		<!-- Organization Type -->
		<div>
			<x-input-label for="organization_type_id" :value="__('Organization Type')" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" />
			<select name="organization_type_id" 
				id="organization_type_id" 
				class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
				<option value="">Select Organization Type (Optional)</option>
				@foreach($organizationTypes as $organizationType)
					<option value="{{ $organizationType->id }}" {{ old('organization_type_id') == $organizationType->id ? 'selected' : '' }}>
						{{ $organizationType->name }}
					</option>
				@endforeach
			</select>
			<x-input-error :messages="$errors->get('organization_type_id')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
		</div>

		<!-- Password -->
		<div>
			<x-input-label for="password" :value="__('Password')" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" />
			<x-text-input id="password" 
				class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
				type="password"
				name="password"
				required 
				autocomplete="new-password" 
				placeholder="Create a password" />
			<x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
		</div>

		<!-- Confirm Password -->
		<div>
			<x-input-label for="password_confirmation" :value="__('Confirm Password')" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" />
			<x-text-input id="password_confirmation" 
				class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
				type="password"
				name="password_confirmation" 
				required 
				autocomplete="new-password" 
				placeholder="Confirm your password" />
			<x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600 dark:text-red-400" />
		</div>

		<!-- Terms and Conditions -->
		<div class="flex items-center">
			<input id="terms" 
				type="checkbox" 
				class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700" 
				required>
			<label for="terms" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
				I agree to the 
				<a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">Terms of Service</a> 
				and 
				<a href="#" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">Privacy Policy</a>
			</label>
		</div>

		<!-- Submit Button -->
		<div>
			<x-primary-button class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
				{{ __('Create Account') }}
			</x-primary-button>
		</div>

		<!-- Login Link -->
		<div class="text-center">
			<p class="text-sm text-gray-600 dark:text-gray-400">
				Already have an account? 
				<a href="{{ route('login') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
					{{ __('Sign in') }}
				</a>
			</p>
		</div>
	</form>
</x-guest-layout>