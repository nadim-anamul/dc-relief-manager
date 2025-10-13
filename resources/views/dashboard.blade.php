<x-main-layout>
    <x-slot name="header">
		<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-4 lg:space-y-0">
			<div class="flex items-center space-x-3">
				<div class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl">
					<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
						</path>
					</svg>
				</div>
				<div>
					<h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Relief Management Dashboard') }}
					</h1>
					<p class="text-sm text-gray-500 dark:text-gray-400">
						{{ __('Comprehensive overview of relief operations and analytics') }}</p>
				</div>
			</div>
			<div class="flex flex-wrap gap-3">
				<a href="{{ route('admin.projects.create') }}"
					class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M12 6v6m0 0v6m0-6h6m-6 0H6">
						</path>
					</svg>
                    নতুন বরাদ্দ এন্ট্রি
				</a>
				<a href="{{ route('admin.relief-applications.create') }}"
					class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
					<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
					</svg>
                    {{ __('Quick Actions') }}
				</a>
			</div>
		</div>
    </x-slot>

	<div class="space-y-8">
		@if(isset($years) && isset($selectedYearId))
		<!-- Smart Filter Row (mobile responsive) -->
		<div class="w-full">
			<form id="smartFilter" method="GET" action="{{ route('dashboard') }}"
				class="w-full relative overflow-hidden rounded-2xl shadow-xl">
				<div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-cyan-500/10 pointer-events-none"></div>
				
				<!-- Desktop Layout -->
				<div class="hidden sm:flex relative w-full bg-gradient-to-r from-white/80 via-white/70 to-white/80 dark:from-gray-900/80 dark:via-gray-900/70 dark:to-gray-900/80 backdrop-blur-xl border border-white/50 dark:border-gray-700/50 px-6 py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
					<div class="flex items-center justify-between gap-6 w-full">
						<!-- Economic Year Selector -->
						<div class="flex items-center gap-4 flex-1">
							<div class="flex items-center gap-4">
								<label class="text-sm font-semibold text-gray-700 dark:text-gray-200 whitespace-nowrap">
									{{ __('Economic Year') }}:
								</label>
								<div class="relative group">
									<!-- Glowing border effect -->
									<div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl opacity-0 group-hover:opacity-20 transition-opacity duration-300 blur-sm"></div>
									
									<select id="economic_year_id_desktop" name="economic_year_id"
										class="relative appearance-none w-full pl-5 pr-14 py-4 bg-gradient-to-r from-white/90 to-white/80 dark:from-gray-800/90 dark:to-gray-700/80 border-2 border-transparent rounded-2xl text-gray-900 dark:text-gray-100 font-semibold shadow-lg hover:shadow-xl focus:ring-4 focus:ring-indigo-500/30 focus:border-indigo-400 transition-all duration-300 cursor-pointer backdrop-blur-sm z-10 min-w-[280px]">
										@foreach($years as $y)
											<option value="{{ $y->id }}" {{ $selectedYearId == $y->id ? 'selected' : '' }}>
												{{ $y->name ?? ($y->start_date?->format('Y') . ' - ' . $y->end_date?->format('Y')) }}
											</option>
										@endforeach
									</select>
									
									<!-- Custom dropdown arrow with animation -->
									<div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none">
										<div class="relative">
											<svg class="w-7 h-7 text-gray-400 group-hover:text-indigo-500 transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
											</svg>
											<!-- Subtle glow effect -->
											<div class="absolute inset-0 bg-indigo-400 rounded-full opacity-0 group-hover:opacity-30 blur-md transition-opacity duration-300"></div>
										</div>
									</div>
									
									<!-- Focus indicator -->
									<div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-indigo-500/10 to-purple-500/10 opacity-0 group-focus-within:opacity-100 transition-opacity duration-300"></div>
								</div>
							</div>
						</div>
						
						@if(isset($zillas))
						<!-- Hidden zilla field for data filtering -->
						<input type="hidden" id="zilla_id" name="zilla_id" value="{{ $selectedZillaId ?? '' }}">
						@endif
						
						<!-- Action Buttons -->
						<div class="flex items-center gap-3">
							<a href="{{ route('dashboard') }}"
								class="group inline-flex items-center px-4 py-3 text-sm font-medium rounded-xl border border-gray-200/60 dark:border-gray-600/60 text-gray-700 dark:text-gray-200 bg-white/50 dark:bg-gray-800/50 hover:bg-white/80 dark:hover:bg-gray-800/80 hover:border-gray-300 dark:hover:border-gray-500 transition-all duration-200 shadow-sm hover:shadow-md">
								<svg class="w-4 h-4 mr-2 group-hover:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
								</svg>
								{{ __('Reset') }}
							</a>
							<button type="submit"
								class="group inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
								<svg class="w-4 h-4 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
								</svg>
								{{ __('Apply') }}
							</button>
						</div>
					</div>
				</div>

				<!-- Mobile Layout -->
				<div class="block sm:hidden relative w-full bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl border border-white/40 dark:border-gray-700/60 p-4">
					<div class="space-y-4">
						<!-- Economic Year Selector -->
						<div class="space-y-2">
							<label class="text-sm font-medium text-gray-700 dark:text-gray-300">
								{{ __('Economic Year') }}
							</label>
							<div class="relative">
								<svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1" />
								</svg>
								<select id="economic_year_id_mobile" name="economic_year_id"
									class="w-full appearance-none pl-9 pr-8 py-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-base">
									@foreach($years as $y)
										<option value="{{ $y->id }}" {{ $selectedYearId == $y->id ? 'selected' : '' }}>
											{{ $y->name ?? ($y->start_date?->format('Y') . ' - ' . $y->end_date?->format('Y')) }}
										</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<!-- Action Buttons -->
						<div class="flex gap-3">
							<a href="{{ route('dashboard') }}"
								class="flex-1 inline-flex items-center justify-center px-4 py-3 text-sm font-medium rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
								<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
								</svg>
								{{ __('Reset') }}
							</a>
							<button type="submit"
								class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md transition-colors duration-200">
								<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8m-7 4h6" />
								</svg>
								{{ __('Apply') }}
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		@endif
		<!-- Dashboard Overview Section with Key Metrics -->
		{{-- <div
			class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-2xl p-8 text-white shadow-2xl">
			<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
				<!-- Total Applications -->
				<div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 hover:bg-white/20 transition-all duration-200 cursor-pointer"
					onclick="scrollToSection('application-status')">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-blue-100 text-sm font-medium">{{ __('Total Applications') }}</p>
							<p class="text-3xl font-bold text-white">@bn($stats['totalApplications'] ?? 0)</p>
							<p class="text-blue-200 text-xs mt-1">{{ __('This year') }}</p>
						</div>
						<div class="p-3 bg-blue-500/30 rounded-xl">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
								</path>
							</svg>
				</div>
			</div>
		</div>

				<!-- Active Projects -->
				<div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 hover:bg-white/20 transition-all duration-200 cursor-pointer"
					onclick="scrollToSection('project-overview')">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-blue-100 text-sm font-medium">{{ __('Active Projects') }}</p>
							<p class="text-3xl font-bold text-white">@bn($stats['activeProjects'] ?? 0)</p>
							<p class="text-blue-200 text-xs mt-1">{{ __('Current year') }}</p>
						</div>
						<div class="p-3 bg-purple-500/30 rounded-xl">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
								</path>
							</svg>
						</div>
					</div>
				</div>

				<!-- Pending Reviews -->
				<div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 hover:bg-white/20 transition-all duration-200 cursor-pointer"
					onclick="scrollToSection('application-status')">
					<div class="flex items-center justify-between">
						<div>
							<p class="text-blue-100 text-sm font-medium">{{ __('Pending Reviews') }}</p>
							<p class="text-3xl font-bold text-white">@bn($stats['pendingApplications'] ?? 0)</p>
							<p class="text-blue-200 text-xs mt-1">{{ __('Awaiting approval') }}</p>
						</div>
						<div class="p-3 bg-yellow-500/30 rounded-xl">
							<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
							</svg>
						</div>
					</div>
				</div>
			</div>
		</div> --}}

		<!-- Relief Allocation Card - Full Width -->
		<div class="space-y-8">
			<!-- Relief Allocation Card -->
			<div id="relief-allocation"
				class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			
			<!-- Header Section - Mobile Responsive -->
			<div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
				<!-- Desktop Header -->
				<div class="hidden sm:flex items-center justify-between">
					<div class="flex items-center gap-3">
						<div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
							<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18" />
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
								{{ __('Allocation by Relief Type') }}</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">
								{{ __('Allocated vs Used vs Available for the selected year') }}</p>
						</div>
					</div>
					<div class="flex items-center gap-2">
						<a href="{{ route('admin.projects.index') }}"
							class="inline-flex items-center px-3 py-1.5 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 text-sm font-medium rounded-lg transition-colors duration-200">{{ __('View Projects') }}</a>
						<!-- Sort pills -->
						<form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
							<input type="hidden" name="economic_year_id" value="{{ $selectedYearId }}" />
							@if(isset($selectedZillaId) && $selectedZillaId)
								<input type="hidden" name="zilla_id" value="{{ $selectedZillaId }}" />
							@endif
								<button name="sort" value="allocated"
									class="px-2.5 py-1 text-xs rounded-full border {{ ($currentSort ?? '') === 'allocated' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200' }}">{{ __('Allocated') }}</button>
								<button name="sort" value="used"
									class="px-2.5 py-1 text-xs rounded-full border {{ ($currentSort ?? '') === 'used' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200' }}">{{ __('Used') }}
									%</button>
								<button name="sort" value="available"
									class="px-2.5 py-1 text-xs rounded-full border {{ ($currentSort ?? '') === 'available' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200' }}">{{ __('Available') }}</button>
						</form>
					</div>
				</div>

				<!-- Mobile Header -->
				<div class="block sm:hidden space-y-4">
					<div class="flex items-center gap-3">
						<div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg">
							<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18" />
							</svg>
						</div>
						<div class="flex-1">
							<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
								{{ __('Allocation by Relief Type') }}</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">
								{{ __('Allocated vs Used vs Available for the selected year') }}</p>
						</div>
					</div>
					
					<!-- Mobile Actions -->
					<div class="flex flex-col gap-3">
						<a href="{{ route('admin.projects.index') }}"
							class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 text-sm font-medium rounded-lg transition-colors duration-200">
							<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
							</svg>
							{{ __('View Projects') }}
						</a>
						
						<!-- Mobile Sort Options -->
						<div class="space-y-2">
							<label class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Sort by') }}</label>
							<form method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-3 gap-2">
								<input type="hidden" name="economic_year_id" value="{{ $selectedYearId }}" />
								@if(isset($selectedZillaId) && $selectedZillaId)
									<input type="hidden" name="zilla_id" value="{{ $selectedZillaId }}" />
								@endif
								<button name="sort" value="allocated"
									class="px-3 py-2 text-sm rounded-lg border {{ ($currentSort ?? '') === 'allocated' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }} transition-colors duration-200">{{ __('Allocated') }}</button>
								<button name="sort" value="used"
									class="px-3 py-2 text-sm rounded-lg border {{ ($currentSort ?? '') === 'used' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }} transition-colors duration-200">{{ __('Used') }}</button>
								<button name="sort" value="available"
									class="px-3 py-2 text-sm rounded-lg border {{ ($currentSort ?? '') === 'available' ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }} transition-colors duration-200">{{ __('Available') }}</button>
							</form>
						</div>
					</div>
				</div>
			</div>
				<!-- All Relief Types Visible -->
			<div class="p-6">
				<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
					@forelse($stats['reliefTypeAllocationStats'] as $allocation)
							<a href="{{ route('admin.projects.index', ['economic_year_id' => $selectedYearId, 'relief_type_id' => $allocation->relief_type_id]) }}"
								class="block rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 p-4 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
						<div class="flex items-center justify-between">
							<div class="flex items-center gap-2">
								@if($allocation->reliefType?->color_code)
											<span class="w-3 h-3 rounded-full"
												style="background-color: {{ $allocation->reliefType->color_code }}"></span>
								@endif
										<span
											class="font-medium text-gray-900 dark:text-white">{{ localized_attr($allocation->reliefType, 'name') ?? __('Unknown Type') }}</span>
							</div>
									<span class="text-xs text-gray-500 dark:text-gray-300">@bn($allocation->project_count)
										{{ __('projects') }}</span>
						</div>
						<div class="mt-3 space-y-1 text-sm">
									<div class="flex justify-between"><span
											class="text-gray-600 dark:text-gray-300">{{ __('Allocated') }}</span><span
											class="font-semibold text-gray-900 dark:text-white">{{ bn_number($allocation->formatted_allocated) }}</span>
									</div>
									<div class="flex justify-between"><span
											class="text-gray-600 dark:text-gray-300">{{ __('Used') }}</span><span
											class="font-semibold text-gray-900 dark:text-white">{{ bn_number($allocation->formatted_used) }}</span>
									</div>
									<div class="flex justify-between"><span
											class="text-gray-600 dark:text-gray-300">{{ __('Available') }}</span><span
											class="font-semibold text-gray-900 dark:text-white">{{ bn_number($allocation->formatted_available) }}</span>
									</div>
							<div class="w-full h-2 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
										<div class="h-2 bg-indigo-500"
											style="width: {{ (int) round(($allocation->used_ratio ?? 0) * 100) }}%"></div>
							</div>
									<div class="text-xs text-gray-500 dark:text-gray-400">
										@bn((int) round(($allocation->used_ratio ?? 0) * 100))% {{ __('used') }}</div>
						</div>
					</a>
					@empty
							<div class="col-span-full text-center text-sm text-gray-500 dark:text-gray-400">
								{{ __('No allocations found for the selected year.') }}</div>
					@endforelse
				</div>
			</div>
		</div>
						</div>

		<!-- Application Status Overview Card - Full Width -->
		<div id="application-status"
			class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
				<div class="flex items-center gap-3">
						<div class="p-2 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg">
							<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
							</path>
							</svg>
						</div>
						<div>
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
							{{ __('Application Status Overview') }}</h3>
						<p class="text-sm text-gray-500 dark:text-gray-400">
							{{ __('Current status distribution of relief applications') }}</p>
						</div>
					</div>
				<div class="flex items-center gap-2">
					<a href="{{ route('admin.relief-applications.index') }}"
						class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-lg transition-colors duration-200">
                        {{ __('View All') }}
					</a>
				</div>
			</div>
			<!-- Summary View (Always Visible) -->
			<div class="p-6">
				<div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
					<!-- Pending Applications -->
					<a href="{{ route('admin.relief-applications.index', ['status' => 'pending', 'economic_year_id' => $selectedYearId]) }}"
						class="block bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-xl p-6 border border-yellow-200 dark:border-yellow-800 hover:from-yellow-100 hover:to-orange-100 dark:hover:from-yellow-900/30 dark:hover:to-orange-900/30 transition-all duration-200">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-sm font-medium text-gray-800 dark:text-white">
									{{ __('Pending Review') }}</p>
								<p class="text-3xl font-bold text-gray-900 dark:text-white">
									@bn($stats['pendingApplications'])</p>
								<p class="text-xs text-gray-900 dark:text-gray-200 mt-1">
									{{ __('Awaiting approval') }}</p>
							</div>
							<div class="p-3 bg-yellow-500 rounded-xl">
								<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								</svg>
							</div>
						</div>
					</a>

					<!-- Approved Applications -->
					<a href="{{ route('admin.relief-applications.index', ['status' => 'approved', 'economic_year_id' => $selectedYearId]) }}"
						class="block bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800 hover:from-green-100 hover:to-emerald-100 dark:hover:from-green-900/30 dark:hover:to-emerald-900/30 transition-all duration-200">
						<div class="flex items-center justify-between">
							<div>
								<p class="text-sm font-medium text-green-700 dark:text-green-300">{{ __('Approved') }}
								</p>
								<p class="text-3xl font-bold text-green-800 dark:text-green-200">
									@bn($stats['approvedApplications'])</p>
								<p class="text-xs text-green-600 dark:text-green-400 mt-1">
									{{ __('Successfully processed') }}</p>
							</div>
							<div class="p-3 bg-green-500 rounded-xl">
								<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								</svg>
							</div>
						</div>
					</a>

					<!-- Rejected Applications -->
					<a href="{{ route('admin.relief-applications.index', ['status' => 'rejected', 'economic_year_id' => $selectedYearId]) }}"
						class="block bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-xl p-6 border border-red-200 dark:border-red-800 hover:from-red-100 hover:to-pink-100 dark:hover:from-red-900/30 dark:hover:to-pink-900/30 transition-all duration-200">
						<div class="flex items-center justify-between">
							<div>
                                <p class="text-sm font-medium text-red-700 dark:text-red-300">{{ __('Rejected') }}</p>
								<p class="text-3xl font-bold text-red-800 dark:text-red-200">
									@bn($stats['rejectedApplications'])</p>
                                <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ __('Not eligible') }}</p>
							</div>
							<div class="p-3 bg-red-500 rounded-xl">
								<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
								</svg>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>


		<!-- Quick Access Section -->
		<div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-8 text-white shadow-2xl">
			<div class="flex flex-col lg:flex-row lg:justify-between lg:items-center space-y-6 lg:space-y-0">
				<div class="flex-1">
					<h2 class="text-2xl font-bold mb-2 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
						{{ __('Advanced Analytics & Reports') }}</h2>
					<p class="text-indigo-100 text-lg {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
						{{ __('Access comprehensive distribution analysis and detailed reporting tools') }}</p>
				</div>
				<div class="flex flex-wrap gap-4">
					<a href="{{ route('admin.distributions.consolidated') }}"
						class="inline-flex items-center px-6 py-3 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
						<svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
							</path>
						</svg>
						{{ __('Consolidated Analysis') }}
					</a>
				</div>
			</div>
		</div>

		<!-- Distribution Summary Cards -->
		<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
			<!-- Project × Upazila Distribution Summary -->
			<div
				class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
					<div class="flex items-center gap-3">
						<div class="p-2 bg-gradient-to-r from-green-500 to-teal-600 rounded-lg">
								<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
								</path>
								</svg>
							</div>
							<div>
							<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
								{{ __('Project × Upazila Distribution') }}</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Distribution across upazilas') }}
							</p>
							</div>
						</div>
					<div class="flex items-center gap-2">
						<span
							class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
							@bn($stats['upazilaSummary']->count() ?? 0) {{ __('upazilas') }}
						</span>
					</div>
				</div>
				<div class="p-6">
					<div class="space-y-4">
						{{-- <div class="grid grid-cols-2 gap-4">
							<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
								<div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Amount') }}</div>
								<div class="text-xl font-bold text-gray-900 dark:text-white">
									@if($stats['upazilaSummary'] && $stats['upazilaSummary']->count() > 0)
										@moneybn($stats['upazilaSummary']->sum('total_amount'))
									@else
										@moneybn(0)
									@endif
					</div>
							</div>
							<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
								<div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Applications') }}
							</div>
								<div class="text-xl font-bold text-gray-900 dark:text-white">
									@if($stats['upazilaSummary'] && $stats['upazilaSummary']->count() > 0)
										@bn($stats['upazilaSummary']->sum('application_count'))
									@else
										@bn(0)
									@endif
						</div>
						</div>
						</div> --}}

						<!-- Upazila Application Distribution Chart -->
						<div class="space-y-3">
							<h4 class="text-sm font-medium text-gray-900 dark:text-white">
								{{ __('Application Distribution by Upazila') }}</h4>
							@if($stats['allUpazilas'] && $stats['allUpazilas']->count() > 0)
								<div class="relative h-64">
									<canvas id="upazilaApplicationChart"></canvas>
					</div>
							@else
								<div class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">
									{{ __('No data available') }}</div>
							@endif
				</div>

						<!-- Top 3 Upazilas -->
						{{-- <div class="space-y-2">
							<h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Top Upazilas') }}</h4>
							@if($stats['upazilaSummary'] && $stats['upazilaSummary']->count() > 0)
								@foreach($stats['upazilaSummary']->take(3) as $index => $upazila)
									<div class="flex items-center justify-between text-sm">
										<div class="flex items-center gap-2">
											<span
												class="w-6 h-6 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full flex items-center justify-center text-xs font-medium">
												{{ $index + 1 }}
											</span>
											<span class="text-gray-900 dark:text-white truncate">
												{{ $stats['upazilaNames'][$upazila->upazila_id] ?? ('Upazila #' . $upazila->upazila_id) }}
											</span>
					</div>
										<div class="text-right">
											<div class="font-medium text-gray-900 dark:text-white">
												@moneybn($upazila->total_amount)</div>
											<div class="text-xs text-gray-500 dark:text-gray-400">
												@bn($upazila->application_count) {{ __('apps') }}</div>
				</div>
		</div>
								@endforeach
							@else
								<div class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
									{{ __('No data available') }}</div>
							@endif
						</div> --}}

						<a href="{{ route('admin.distributions.detailed', ['type' => 'upazila', 'economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}"
							class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
							{{ __('View Detailed Distribution') }}
				</a>
			</div>
			</div>
			</div>

			<!-- Duplicate Allocations Summary -->
			<div
				class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
					<div class="flex items-center gap-3">
						<div class="p-2 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg">
							<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
								</path>
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
								{{ __('Duplicate Allocations') }}</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Same organization in year') }}
							</p>
						</div>
				</div>
				<div class="flex items-center gap-2">
						<span
							class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
							@bn($stats['duplicateAllocations']->count() ?? 0) {{ __('organizations') }}
						</span>
				</div>
			</div>
				<div class="p-6">
					<div class="space-y-4">
						<div class="grid grid-cols-2 gap-4">
							<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
								<div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Duplicates') }}</div>
								<div class="text-xl font-bold text-gray-900 dark:text-white">
									@if($stats['duplicateAllocations'] && $stats['duplicateAllocations']->count() > 0)
										@bn($stats['duplicateAllocations']->sum('allocations'))
                                    @else
										@bn(0)
                                    @endif
								</div>
							</div>
							<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
								<div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Amount') }}</div>
								<div class="text-xl font-bold text-gray-900 dark:text-white">
									@if($stats['duplicateAllocations'] && $stats['duplicateAllocations']->count() > 0)
										@moneybn($stats['duplicateAllocations']->sum('total_approved'))
                                    @else
										@moneybn(0)
                                    @endif
								</div>
							</div>
			</div>
			
						<!-- Top 3 Duplicate Organizations -->
						<div class="space-y-2">
							<h4 class="text-sm font-medium text-gray-900 dark:text-white">
								{{ __('Top Duplicate Organizations') }}</h4>
							@if($stats['duplicateAllocations'] && $stats['duplicateAllocations']->count() > 0)
								@foreach($stats['duplicateAllocations']->take(3) as $index => $duplicate)
									<div class="flex items-center justify-between text-sm">
										<div class="flex items-center gap-2">
											<span
												class="w-6 h-6 bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200 rounded-full flex items-center justify-center text-xs font-medium">
												{{ $index + 1 }}
											</span>
											<span
												class="text-gray-900 dark:text-white truncate">{{ $duplicate->organization_name }}</span>
				</div>
										<div class="text-right">
											<div class="font-medium text-gray-900 dark:text-white">@bn($duplicate->allocations)
												{{ __('apps') }}</div>
											<div class="text-xs text-gray-500 dark:text-gray-400">
												@moneybn($duplicate->total_approved)</div>
										</div>
									</div>
								@endforeach
					@else
								<div class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
									{{ __('No duplicates found') }}</div>
					@endif
						</div>

						<a href="{{ route('admin.distributions.detailed', ['type' => 'duplicates', 'economic_year_id' => $selectedYearId]) }}"
							class="w-full inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
							{{ __('View Duplicate Allocations') }}
						</a>
				</div>
			</div>
		</div>


		</div>

		<!-- Additional Analysis Cards -->
		{{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-8"> --}}
			<!-- Project × Upazila × Union Distribution Summary -->
			{{-- <div
				class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
					<div class="flex items-center gap-3">
						<div class="p-2 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg">
							<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
								</path>
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Project × Upazila ×
								Union Distribution') }}</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Distribution across unions') }}
							</p>
						</div>
				</div>
				<div class="flex items-center gap-2">
						<span
							class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
							@bn($stats['upazilaUnionSummary']->count() ?? 0) {{ __('unions') }}
						</span>
				</div>
			</div>
				<div class="p-6">
					<div class="space-y-4">
						<div class="grid grid-cols-2 gap-4">
							<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
								<div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Amount') }}</div>
								<div class="text-xl font-bold text-gray-900 dark:text-white">
									@if($stats['upazilaUnionSummary'] && $stats['upazilaUnionSummary']->count() > 0)
									@moneybn($stats['upazilaUnionSummary']->sum('total_amount'))
                                    @else
									@moneybn(0)
                                    @endif
								</div>
							</div>
							<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
								<div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Applications') }}
								</div>
								<div class="text-xl font-bold text-gray-900 dark:text-white">
									@if($stats['upazilaUnionSummary'] && $stats['upazilaUnionSummary']->count() > 0)
									@bn($stats['upazilaUnionSummary']->sum('application_count'))
                                    @else
									@bn(0)
                                    @endif
								</div>
							</div>
			</div>
			
						<!-- Top 3 Unions -->
						<div class="space-y-2">
							<h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Top Unions') }}</h4>
							@if($stats['upazilaUnionSummary'] && $stats['upazilaUnionSummary']->count() > 0)
							@foreach($stats['upazilaUnionSummary']->take(3) as $index => $union)
							<div class="flex items-center justify-between text-sm">
								<div class="flex items-center gap-2">
									<span
										class="w-6 h-6 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full flex items-center justify-center text-xs font-medium">
										{{ $index + 1 }}
									</span>
									<span class="text-gray-900 dark:text-white truncate">
										{{ $stats['unionNames'][$union->union_id] ?? ('Union #'.$union->union_id) }}
									</span>
				</div>
								<div class="text-right">
									<div class="font-medium text-gray-900 dark:text-white">
										@moneybn($union->total_amount)</div>
									<div class="text-xs text-gray-500 dark:text-gray-400">@bn($union->application_count)
										{{ __('apps') }}</div>
								</div>
							</div>
							@endforeach
					@else
							<div class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">{{ __('No data
								available') }}</div>
					@endif
						</div>

						<a href="{{ route('admin.distributions.detailed', ['type' => 'union', 'economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}"
							class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
							{{ __('View Detailed Distribution') }}
						</a>
					</div>
				</div>
			</div> --}}

			<!-- Active Project Allocations Summary -->
			{{-- <div
				class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
					<div class="flex items-center gap-3">
						<div class="p-2 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg">
							<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
									d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
								</path>
							</svg>
						</div>
						<div>
							<h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Active Project
								Allocations') }}</h3>
							<p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Current year project
								allocations') }}</p>
						</div>
					</div>
					<div class="flex items-center gap-2">
						<span
							class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
							@bn($stats['projectAllocationStats']->count() ?? 0) {{ __('projects') }}
						</span>
					</div>
				</div>
				<div class="p-6">
					<div class="space-y-4">
						<div class="grid grid-cols-2 gap-4">
							<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
								<div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Allocated') }}</div>
								<div class="text-xl font-bold text-gray-900 dark:text-white">
									@if($stats['projectAllocationStats'] && $stats['projectAllocationStats']->count() >
									0)
									@moneybn($stats['projectAllocationStats']->sum('allocated_amount'))
					@else
									@moneybn(0)
					@endif
				</div>
			</div>
							<div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
								<div class="text-sm text-gray-600 dark:text-gray-400">{{ __('Top Project') }}</div>
								<div class="text-lg font-bold text-gray-900 dark:text-white">
									@if($stats['projectAllocationStats'] && $stats['projectAllocationStats']->count() >
									0)
									@moneybn($stats['projectAllocationStats']->first()->allocated_amount ?? 0)
									@else
									@moneybn(0)
			@endif
								</div>
							</div>
		</div>

						<!-- Top 3 Projects -->
						<div class="space-y-2">
							<h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Top Projects') }}</h4>
							@if($stats['projectAllocationStats'] && $stats['projectAllocationStats']->count() > 0)
							@foreach($stats['projectAllocationStats']->take(3) as $index => $project)
							<div class="flex items-center justify-between text-sm">
								<div class="flex items-center gap-2">
									<span
										class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200 rounded-full flex items-center justify-center text-xs font-medium">
										{{ $index + 1 }}
									</span>
									<span class="text-gray-900 dark:text-white truncate">{{ $project->name }}</span>
								</div>
								<div class="text-right">
									<div class="font-medium text-gray-900 dark:text-white">
										@moneybn($project->allocated_amount)</div>
									<div class="text-xs text-gray-500 dark:text-gray-400">{{
										localized_attr($project->reliefType, 'name') ?? __('Unknown Type') }}</div>
								</div>
							</div>
							@endforeach
							@else
							<div class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">{{ __('No projects
								found') }}</div>
							@endif
						</div>

						<a href="{{ route('admin.distributions.detailed', ['type' => 'projects', 'economic_year_id' => $selectedYearId]) }}"
							class="w-full inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
							{{ __('View Project Allocations') }}
						</a>
					</div>
				</div>
			</div> --}}
			{{--
		</div> --}}

		<!-- Coverage Gaps (conditional) -->
		@if(!empty($selectedZillaId))
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
				<div
					class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
							{{ __('Unserved Upazilas (Selected Zilla & Year)') }}</h3>
				</div>
				<div class="p-6">
					@php
						$unservedUpazilaNames = collect($stats['coverage']['unserved_upazila_ids'] ?? [])
								->map(function ($id) use ($stats)
								{
									return $stats['upazilaNames'][$id] ?? ('#' . $id); })
							->implode(', ');
					@endphp
					<p class="text-sm text-gray-900 dark:text-gray-100">
                        {{ $unservedUpazilaNames !== '' ? $unservedUpazilaNames : __('All upazilas have distributions.') }}
					</p>
				</div>
			</div>
				<div
					class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
				<div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
						<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
							{{ __('Unserved Unions (Selected Zilla & Year)') }}</h3>
				</div>
				<div class="p-6">
					@php
						$unservedUnionNames = collect($stats['coverage']['unserved_union_ids'] ?? [])
								->map(function ($id) use ($stats)
								{
									return $stats['unionNames'][$id] ?? ('#' . $id); })
							->implode(', ');
					@endphp
					<p class="text-sm text-gray-900 dark:text-gray-100">
                        {{ $unservedUnionNames !== '' ? $unservedUnionNames : __('All unions have distributions.') }}
					</p>
				</div>
			</div>
		</div>
		@endif
		<!-- Quick Navigation Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
			<!-- Upazila Distribution -->
			<a href="{{ route('admin.distributions.detailed', ['type' => 'upazila', 'economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}"
				class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200 hover:border-green-300 dark:hover:border-green-600">
				<div class="flex items-center justify-between mb-4">
					<div
						class="p-3 bg-green-100 dark:bg-green-900 rounded-lg group-hover:scale-110 transition-transform duration-200">
						<svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
							viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
							</path>
								</svg>
							</div>
					<svg class="w-5 h-5 text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200"
						fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
								</svg>
							</div>
				<h3
					class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors duration-200 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
					{{ __('Upazila Distribution') }}
				</h3>
				<p class="text-sm text-gray-500 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
					{{ __('Detailed project distribution across upazilas') }}
				</p>
			</a>

			<!-- Union Distribution -->
			<a href="{{ route('admin.distributions.detailed', ['type' => 'union', 'economic_year_id' => $selectedYearId, 'zilla_id' => $selectedZillaId]) }}"
				class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200 hover:border-purple-300 dark:hover:border-purple-600">
				<div class="flex items-center justify-between mb-4">
					<div
						class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg group-hover:scale-110 transition-transform duration-200">
						<svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
							viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
							</path>
								</svg>
							</div>
					<svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200"
						fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
					</svg>
							</div>
				<h3
					class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
					{{ __('Union Distribution') }}
				</h3>
				<p class="text-sm text-gray-500 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
					{{ __('Detailed project distribution across unions') }}
				</p>
			</a>

			<!-- Duplicate Allocations -->
			<a href="{{ route('admin.distributions.detailed', ['type' => 'duplicates', 'economic_year_id' => $selectedYearId]) }}"
				class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200 hover:border-orange-300 dark:hover:border-orange-600">
				<div class="flex items-center justify-between mb-4">
					<div
						class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg group-hover:scale-110 transition-transform duration-200">
						<svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
							viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
							</path>
								</svg>
							</div>
					<svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-200"
						fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
								</svg>
							</div>
				<h3
					class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors duration-200 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
					{{ __('Duplicate Allocations') }}
				</h3>
				<p class="text-sm text-gray-500 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
					{{ __('Organizations with multiple allocations') }}
				</p>
			</a>

			<!-- Project Allocations -->
			<a href="{{ route('admin.distributions.detailed', ['type' => 'projects', 'economic_year_id' => $selectedYearId]) }}"
				class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all duration-200 hover:border-emerald-300 dark:hover:border-emerald-600">
				<div class="flex items-center justify-between mb-4">
					<div
						class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-lg group-hover:scale-110 transition-transform duration-200">
						<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
							viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
							</path>
							</svg>
					</div>
					<svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-200"
						fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
							</svg>
					</div>
				<h3
					class="text-lg font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors duration-200 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
					{{ __('Project Allocations') }}
				</h3>
				<p class="text-sm text-gray-500 dark:text-gray-400 {{ app()->isLocale('bn') ? 'font-sans' : '' }}">
					{{ __('Active project allocations with applications') }}
				</p>
			</a>
		</div>
	</div>

	<!-- Chart.js CDN -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	
	<script>
		// Chart data from PHP
		const chartData = @json($chartData);
		
		// Application Status Pie Chart
		const statusCanvas = document.getElementById('statusChart');
		if (statusCanvas) {
			const statusCtx = statusCanvas.getContext('2d');
			new Chart(statusCtx, {
			type: 'pie',
			data: {
				labels: chartData.statusData.labels,
				datasets: [{
					data: chartData.statusData.data,
					backgroundColor: chartData.statusData.colors,
					borderWidth: 2,
					borderColor: '#fff'
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						position: 'bottom',
						labels: {
							color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
						}
					}
				}
			}
			});
		}

		// Area-wise Relief Distribution Bar Chart
		const areaCanvas = document.getElementById('areaChart');
		if (areaCanvas) {
			const areaCtx = areaCanvas.getContext('2d');
			new Chart(areaCtx, {
			type: 'bar',
			data: {
				labels: chartData.areaData.map(item => item.zilla_name),
				datasets: [{
					label: 'Relief Amount (৳)',
					data: chartData.areaData.map(item => item.total_amount),
					backgroundColor: 'rgba(59, 130, 246, 0.8)',
					borderColor: 'rgba(59, 130, 246, 1)',
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					y: {
						beginAtZero: true,
						ticks: {
							color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
								callback: function (value) {
								return '৳' + value.toLocaleString();
							}
						},
						grid: {
							color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
						}
					},
					x: {
						ticks: {
							color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
						},
						grid: {
							color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
						}
					}
				},
				plugins: {
					legend: {
						labels: {
							color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
						}
					}
				}
			}
			});
		}

		// Organization Type Distribution Doughnut Chart (only if canvas exists)
		const orgTypeCanvas = document.getElementById('orgTypeChart');
		if (orgTypeCanvas) {
			const orgTypeCtx = orgTypeCanvas.getContext('2d');
			new Chart(orgTypeCtx, {
				type: 'doughnut',
				data: {
					labels: chartData.orgTypeData.map(item => item.org_type_name || 'Not Specified'),
					datasets: [{
						data: chartData.orgTypeData.map(item => item.total_amount),
						backgroundColor: [
							'#ef4444', '#f97316', '#eab308', '#22c55e', '#06b6d4', '#8b5cf6', '#ec4899'
						],
						borderWidth: 2,
						borderColor: '#fff'
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							position: 'bottom',
							labels: {
								color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
							}
						}
					}
				}
			});
		}

		// Relief Type Distribution Bar Chart (only if canvas exists)
		const reliefTypeCanvas = document.getElementById('reliefTypeChart');
		if (reliefTypeCanvas) {
			const reliefTypeCtx = reliefTypeCanvas.getContext('2d');
			new Chart(reliefTypeCtx, {
				type: 'bar',
				data: {
					labels: chartData.reliefTypeData.map(item => item.relief_type_name),
					datasets: [{
						label: 'Relief Amount (৳)',
						data: chartData.reliefTypeData.map(item => item.total_amount),
						backgroundColor: chartData.reliefTypeData.map(item => item.color_code || '#6b7280'),
						borderColor: chartData.reliefTypeData.map(item => item.color_code || '#6b7280'),
						borderWidth: 1
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						y: {
							beginAtZero: true,
							ticks: {
								color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
								callback: function (value) {
									return '৳' + value.toLocaleString();
								}
							},
							grid: {
								color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
							}
						},
						x: {
							ticks: {
								color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
							},
							grid: {
								color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
							}
						}
					},
					plugins: {
						legend: {
							labels: {
								color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
							}
						}
					}
				}
			});
		}

		// Trends removed

		// Upazila Application Distribution Bar Chart (only if canvas exists)
		const upazilaApplicationCanvas = document.getElementById('upazilaApplicationChart');
		if (upazilaApplicationCanvas) {
			const upazilaApplicationCtx = upazilaApplicationCanvas.getContext('2d');

			// Prepare data for the chart
			const upazilaData = @json($stats['upazilaSummary'] ?? []);
			const upazilaNames = @json($stats['upazilaNames'] ?? []);
			const allUpazilas = @json($stats['allUpazilas'] ?? []);

			// Create a map of upazila_id to application_count for quick lookup
			const applicationCountMap = {};
			upazilaData.forEach(item => {
				applicationCountMap[item.upazila_id] = item.application_count;
			});

			// Get all upazilas and their application counts (including those with 0)
			const chartLabels = [];
			const chartData = [];

			allUpazilas.forEach(upazila => {
				chartLabels.push(upazilaNames[upazila.id] || `Upazila #${upazila.id}`);
				chartData.push(applicationCountMap[upazila.id] || 0);
			});

			new Chart(upazilaApplicationCtx, {
				type: 'bar',
				data: {
					labels: chartLabels,
					datasets: [{
						label: '{{ __("Approved Applications") }}',
						data: chartData,
						backgroundColor: 'rgba(34, 197, 94, 0.8)',
						borderColor: 'rgba(34, 197, 94, 1)',
						borderWidth: 1,
						borderRadius: 4
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						x: {
							ticks: {
								color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
								font: {
									size: 10
								},
								maxRotation: 45,
								minRotation: 0
							},
							grid: {
								color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
							}
						},
						y: {
							beginAtZero: true,
							ticks: {
								color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
								stepSize: 1,
								callback: function (value) {
									return value % 1 === 0 ? value : '';
								}
							},
							grid: {
								color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
							}
						}
					},
					plugins: {
						legend: {
							display: false
						},
						tooltip: {
							callbacks: {
								label: function (context) {
									return '{{ __("Applications") }}: ' + context.parsed.y;
								}
							}
						}
					}
				}
			});
		}

		// Monthly Relief Distribution Line Chart (only if canvas exists)
		const monthlyCanvas = document.getElementById('monthlyChart');
		if (monthlyCanvas) {
			const monthlyCtx = monthlyCanvas.getContext('2d');
			new Chart(monthlyCtx, {
				type: 'line',
				data: {
					labels: chartData.monthlyData.map(item => {
						const date = new Date(item.month + '-01');
						return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short' });
					}),
					datasets: [{
						label: 'Relief Amount (৳)',
						data: chartData.monthlyData.map(item => item.total_amount),
						borderColor: 'rgba(16, 185, 129, 1)',
						backgroundColor: 'rgba(16, 185, 129, 0.1)',
						borderWidth: 2,
						fill: true,
						tension: 0.4
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						y: {
							beginAtZero: true,
							ticks: {
								color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151',
								callback: function (value) {
									return '৳' + value.toLocaleString();
								}
							},
							grid: {
								color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
							}
						},
						x: {
							ticks: {
								color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
							},
							grid: {
								color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb'
							}
						}
					},
					plugins: {
						legend: {
							labels: {
								color: document.documentElement.classList.contains('dark') ? '#d1d5db' : '#374151'
							}
						}
					}
				}
			});
		}

		// Refresh dashboard function
		function refreshDashboard() {
			window.location.reload();
		}

		// Scroll to section function
		function scrollToSection(sectionId) {
			const element = document.getElementById(sectionId);
			if (element) {
				element.scrollIntoView({
					behavior: 'smooth',
					block: 'start'
				});
				// Add a subtle highlight effect
				element.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
				setTimeout(() => {
					element.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
				}, 2000);
			}
		}

		// Initialize dashboard interactions
		document.addEventListener('DOMContentLoaded', function () {

			// Sync economic year selects between desktop and mobile
			const desktopSelect = document.getElementById('economic_year_id_desktop');
			const mobileSelect = document.getElementById('economic_year_id_mobile');
			
			if (desktopSelect && mobileSelect) {
				// Sync desktop to mobile
				desktopSelect.addEventListener('change', function() {
					mobileSelect.value = this.value;
				});
				
				// Sync mobile to desktop
				mobileSelect.addEventListener('change', function() {
					desktopSelect.value = this.value;
				});
			}

			// Add intersection observer for scroll animations
			const observerOptions = {
				threshold: 0.1,
				rootMargin: '0px 0px -50px 0px'
			};

			const observer = new IntersectionObserver((entries) => {
				entries.forEach(entry => {
					if (entry.isIntersecting) {
						entry.target.classList.add('animate-fade-in');
					}
				});
			}, observerOptions);

			// Observe all dashboard cards
			document.querySelectorAll('.bg-white.dark\\:bg-gray-800.rounded-xl').forEach(card => {
				observer.observe(card);
			});
		});
	</script>

	<style>
		@keyframes fade-in {
			from {
				opacity: 0;
				transform: translateY(20px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.animate-fade-in {
			animation: fade-in 0.6s ease-out forwards;
		}

		/* Custom scrollbar for search results */
		.overflow-y-auto::-webkit-scrollbar {
			width: 6px;
		}

		.overflow-y-auto::-webkit-scrollbar-track {
			background: #f1f1f1;
			border-radius: 3px;
		}

		.overflow-y-auto::-webkit-scrollbar-thumb {
			background: #c1c1c1;
			border-radius: 3px;
		}

		.overflow-y-auto::-webkit-scrollbar-thumb:hover {
			background: #a8a8a8;
		}

		.dark .overflow-y-auto::-webkit-scrollbar-track {
			background: #374151;
		}

		.dark .overflow-y-auto::-webkit-scrollbar-thumb {
			background: #6b7280;
		}

		.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
			background: #9ca3af;
		}
	</style>
</x-main-layout>