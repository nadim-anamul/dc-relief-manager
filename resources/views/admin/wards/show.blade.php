<x-main-layout>
	<x-slot name="header">
		<div class="flex items-center">
			<a href="{{ route('admin.wards.index') }}" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
				</svg>
			</a>
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ward->name }}</h1>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Ward Details -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Ward Information</h3>
			</div>
			<div class="p-6">
				<dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ward->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bengali Name</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ward->name_bn ?? '-' }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Code</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
								{{ $ward->code }}
							</span>
						</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">
							@if($ward->is_active)
								<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
									Active
								</span>
							@else
								<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
									Inactive
								</span>
							@endif
						</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Union</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ward->union->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Upazila</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ward->union->upazila->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Zilla</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ward->union->upazila->zilla->name }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ward->created_at->format('M d, Y') }}</dd>
					</div>
					<div>
						<dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
						<dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $ward->updated_at->format('M d, Y') }}</dd>
					</div>
				</dl>
			</div>
		</div>

		<!-- Hierarchy Breadcrumb -->
		<div class="card">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<h3 class="text-lg font-medium text-gray-900 dark:text-white">Administrative Hierarchy</h3>
			</div>
			<div class="p-6">
				<nav class="flex" aria-label="Breadcrumb">
					<ol class="flex items-center space-x-4">
						<li>
							<div class="flex items-center">
								<a href="{{ route('admin.zillas.show', $ward->union->upazila->zilla) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
									{{ $ward->union->upazila->zilla->name }}
								</a>
							</div>
						</li>
						<li>
							<div class="flex items-center">
								<svg class="flex-shrink-0 h-5 w-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
								</svg>
								<a href="{{ route('admin.upazilas.show', $ward->union->upazila) }}" class="ml-4 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
									{{ $ward->union->upazila->name }}
								</a>
							</div>
						</li>
						<li>
							<div class="flex items-center">
								<svg class="flex-shrink-0 h-5 w-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
								</svg>
								<a href="{{ route('admin.unions.show', $ward->union) }}" class="ml-4 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
									{{ $ward->union->name }}
								</a>
							</div>
						</li>
						<li>
							<div class="flex items-center">
								<svg class="flex-shrink-0 h-5 w-5 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
								</svg>
								<span class="ml-4 text-sm font-medium text-gray-500 dark:text-gray-400" aria-current="page">
									{{ $ward->name }}
								</span>
							</div>
						</li>
					</ol>
				</nav>
			</div>
		</div>

		<!-- Actions -->
		<div class="flex justify-end space-x-3">
			<a href="{{ route('admin.wards.index') }}" class="btn-secondary">
				Back to Wards
			</a>
			<a href="{{ route('admin.wards.edit', $ward) }}" class="btn-primary">
				Edit Ward
			</a>
		</div>
	</div>
</x-main-layout>
