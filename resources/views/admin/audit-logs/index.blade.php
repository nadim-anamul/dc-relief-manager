<x-main-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Audit Logs') }}</h1>
			<div class="flex space-x-3">
				<a href="{{ route('admin.audit-logs.export', request()->query()) }}" 
				   class="btn-success">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
					</svg>
					{{ __('Export CSV') }}
				</a>
				<button onclick="showClearModal()" class="btn-danger">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
					</svg>
					{{ __('Clear Old Logs') }}
				</button>
			</div>
		</div>
	</x-slot>

	<div class="space-y-6">
		<!-- Statistics Cards -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
			<div class="card p-6">
				<div class="flex items-center">
					<div class="flex-shrink-0">
						<div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
							<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
							</svg>
						</div>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Logs') }}</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $auditLogs->total() }}</p>
					</div>
				</div>
			</div>

			<div class="card p-6">
				<div class="flex items-center">
					<div class="flex-shrink-0">
						<div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
							<svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
							</svg>
						</div>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created Today') }}</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $todayLogs }}</p>
					</div>
				</div>
			</div>

			<div class="card p-6">
				<div class="flex items-center">
					<div class="flex-shrink-0">
						<div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
							<svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
							</svg>
						</div>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Updated Today') }}</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $todayUpdates }}</p>
					</div>
				</div>
			</div>

			<div class="card p-6">
				<div class="flex items-center">
					<div class="flex-shrink-0">
						<div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
							<svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
							</svg>
						</div>
					</div>
					<div class="ml-4">
						<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Deleted Today') }}</p>
						<p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $todayDeletes }}</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Filters -->
		<div class="card p-6">
			<form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
				<div>
					<label for="event" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Event Type') }}</label>
					<select name="event" id="event" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
						<option value="all">{{ __('All Events') }}</option>
						@foreach($events as $eventOption)
							<option value="{{ $eventOption }}" {{ $event === $eventOption ? 'selected' : '' }}>
								{{ __('' . ucfirst($eventOption)) }}
							</option>
						@endforeach
					</select>
				</div>

				<div>
					<label for="auditable_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Model Type') }}</label>
					<select name="auditable_type" id="auditable_type" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
						<option value="">{{ __('All Models') }}</option>
						@foreach($auditableTypes as $type)
							<option value="{{ $type }}" {{ $auditableType === $type ? 'selected' : '' }}>
								{{ $type }}
							</option>
						@endforeach
					</select>
				</div>

				<div>
					<label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('User') }}</label>
					<select name="user_id" id="user_id" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
						<option value="">{{ __('All Users') }}</option>
						@foreach($users as $user)
							<option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
								{{ $user->name }}
							</option>
						@endforeach
					</select>
				</div>

				<div>
					<label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Start Date') }}</label>
					<input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
				</div>

				<div>
					<label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('End Date') }}</label>
					<input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
				</div>

				<div class="flex items-end">
					<button type="submit" class="btn-primary w-full">
						<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
						</svg>
						{{ __('Filter') }}
					</button>
				</div>
			</form>
		</div>

		<!-- Audit Logs Table -->
		<div class="card">
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
					<thead class="bg-gray-50 dark:bg-gray-800">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Event') }}</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Model') }}</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('User') }}</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('IP Address') }}</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Date') }}</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</th>
						</tr>
					</thead>
					<tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
						@forelse($auditLogs as $auditLog)
							<tr>
								<td class="px-6 py-4 whitespace-nowrap">
									<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $auditLog->event_badge_class }}">
										{{ $auditLog->event_display }}
									</span>
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
									{{ $auditLog->model_name }}
									@if($auditLog->auditable_id)
										<span class="text-gray-500 dark:text-gray-400">#{{ $auditLog->auditable_id }}</span>
									@endif
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
									{{ $auditLog->user->name ?? 'System' }}
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
									{{ $auditLog->ip_address ?? '-' }}
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
									{{ $auditLog->created_at->format('M d, Y H:i') }}
								</td>
								<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
									<div class="flex space-x-2">
										<a href="{{ route('admin.audit-logs.show', $auditLog) }}" 
										   class="inline-flex items-center px-3 py-1 text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-md transition-colors duration-200"
										   title="{{ __('View Audit Log') }}">
											<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
											</svg>
											{{ __('View') }}
										</a>
										<form method="POST" action="{{ route('admin.audit-logs.destroy', $auditLog) }}" 
											  class="inline" 
											  onsubmit="return confirm('{{ __('Are you sure you want to delete this audit log?') }}')">
											@csrf
											@method('DELETE')
											<button type="submit" 
													class="inline-flex items-center px-3 py-1 text-sm text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-100 dark:hover:bg-red-900 rounded-md transition-colors duration-200"
													title="{{ __('Delete Audit Log') }}">
												<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
												</svg>
												{{ __('Delete') }}
											</button>
										</form>
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
									<div class="flex flex-col items-center">
										<svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
										</svg>
										<h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('No audit logs found') }}</h3>
										<p class="text-gray-500 dark:text-gray-400">{{ __('No audit logs match your current filters.') }}</p>
									</div>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			<!-- Pagination -->
			@if($auditLogs->hasPages())
				<div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
					{{ $auditLogs->links() }}
				</div>
			@endif
		</div>
	</div>

	<!-- Clear Modal -->
	<div id="clearModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
		<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
			<div class="mt-3">
				<div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
					<svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
					</svg>
				</div>
				<div class="mt-2 px-7 py-3">
					<h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Clear Old Audit Logs') }}</h3>
					<div class="mt-2 px-7 py-3">
						<p class="text-sm text-gray-500 dark:text-gray-400">{{ __('This will permanently delete audit logs older than the specified number of days.') }}</p>
					</div>
				</div>
				<form method="POST" action="{{ route('admin.audit-logs.clear') }}">
					@csrf
					<div class="px-7 py-3">
						<label for="days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Delete logs older than (days):') }}</label>
						<input type="number" name="days" id="days" min="1" max="365" value="30" required class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
					</div>
					<div class="items-center px-4 py-3">
						<button type="submit" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
							{{ __('Clear Logs') }}
						</button>
						<button type="button" onclick="hideClearModal()" class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
							{{ __('Cancel') }}
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		function showClearModal() {
			document.getElementById('clearModal').classList.remove('hidden');
		}

		function hideClearModal() {
			document.getElementById('clearModal').classList.add('hidden');
		}
	</script>
</x-main-layout>
