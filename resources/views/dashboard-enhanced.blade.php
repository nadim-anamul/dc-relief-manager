<x-main-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-4 sm:space-y-0">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <button onclick="refreshDashboard()" class="btn-secondary flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 px-4 sm:px-6 lg:px-8">
        <!-- Key Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Total Relief Distributed -->
            <x-responsive-card padding="p-4 sm:p-6" shadow="shadow-sm">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 bg-green-100 dark:bg-green-900 rounded-lg flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Relief Distributed</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900 dark:text-white truncate">৳{{ number_format($stats['totalReliefDistributed'], 2) }}</p>
                    </div>
                </div>
            </x-responsive-card>

            <!-- Total Applications -->
            <x-responsive-card padding="p-4 sm:p-6" shadow="shadow-sm">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 bg-blue-100 dark:bg-blue-900 rounded-lg flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Applications</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['totalApplications'] }}</p>
                    </div>
                </div>
            </x-responsive-card>

            <!-- Remaining Project Budget -->
            <x-responsive-card padding="p-4 sm:p-6" shadow="shadow-sm">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Remaining Budget</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900 dark:text-white truncate">৳{{ number_format($stats['remainingProjectBudget'], 2) }}</p>
                    </div>
                </div>
            </x-responsive-card>

            <!-- Active Projects -->
            <x-responsive-card padding="p-4 sm:p-6" shadow="shadow-sm">
                <div class="flex items-center">
                    <div class="p-2 sm:p-3 bg-purple-100 dark:bg-purple-900 rounded-lg flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Projects</p>
                        <p class="text-lg sm:text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['activeProjects'] }}</p>
                    </div>
                </div>
            </x-responsive-card>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Application Status Chart -->
            <x-responsive-card 
                title="Application Status Overview"
                subtitle="Distribution of applications by status"
                padding="p-4 sm:p-6"
            >
                <div class="h-64 sm:h-80">
                    <canvas id="applicationStatusChart"></canvas>
                </div>
            </x-responsive-card>

            <!-- Area-wise Relief Distribution Chart -->
            <x-responsive-card 
                title="Area-wise Relief Distribution"
                subtitle="Relief distribution across different areas"
                padding="p-4 sm:p-6"
                actions="
                    <div class='flex space-x-2'>
                        <a href='{{ route('admin.exports.area-wise-relief.excel') }}' class='btn-success text-xs'>
                            <svg class='w-3 h-3 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'></path>
                            </svg>
                            Excel
                        </a>
                        <a href='{{ route('admin.exports.area-wise-relief.pdf') }}' class='btn-danger text-xs'>
                            <svg class='w-3 h-3 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'></path>
                            </svg>
                            PDF
                        </a>
                    </div>
                "
            >
                <div class="h-64 sm:h-80">
                    <canvas id="areaWiseChart"></canvas>
                </div>
            </x-responsive-card>
        </div>

        <!-- Data Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Area-wise Allocation Summary -->
            <x-responsive-card 
                title="Area-wise Allocation Summary"
                subtitle="Relief distribution by administrative areas"
                padding="p-4 sm:p-6"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Area</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Count</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($stats['areaWiseStats'] as $area)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $area['name'] }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        ৳{{ number_format($area['total_amount'], 2) }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $area['application_count'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No area-wise data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-responsive-card>

            <!-- Organization Type Summary -->
            <x-responsive-card 
                title="Organization Type Summary"
                subtitle="Applications by organization type"
                padding="p-4 sm:p-6"
            >
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Count</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($stats['organizationTypeStats'] as $orgType)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $orgType['name'] }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $orgType['application_count'] }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        ৳{{ number_format($orgType['total_amount'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No organization type data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-responsive-card>
        </div>

        <!-- Remaining Funds Per Project -->
        <x-responsive-card 
            title="Remaining Funds Per Project"
            subtitle="Available budget for each active project"
            padding="p-4 sm:p-6"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Budget</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Used</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Remaining</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($stats['projectBudgets'] as $project)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    <div class="max-w-xs truncate">{{ $project['name'] }}</div>
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ৳{{ number_format($project['budget'], 2) }}
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ৳{{ number_format($project['used_amount'], 2) }}
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    ৳{{ number_format($project['remaining_amount'], 2) }}
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $project['remaining_amount'] > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ $project['remaining_amount'] > 0 ? 'Available' : 'Exhausted' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No project data available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-responsive-card>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart data from PHP
        const chartData = @json($chartData);

        // Application Status Chart
        const applicationStatusCtx = document.getElementById('applicationStatusChart').getContext('2d');
        new Chart(applicationStatusCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.applicationStatus.labels,
                datasets: [{
                    data: chartData.applicationStatus.data,
                    backgroundColor: [
                        '#10B981', // green-500
                        '#F59E0B', // amber-500
                        '#EF4444'  // red-500
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });

        // Area-wise Relief Chart
        const areaWiseCtx = document.getElementById('areaWiseChart').getContext('2d');
        new Chart(areaWiseCtx, {
            type: 'bar',
            data: {
                labels: chartData.areaWise.labels,
                datasets: [{
                    label: 'Relief Amount (৳)',
                    data: chartData.areaWise.data,
                    backgroundColor: '#3B82F6',
                    borderColor: '#1D4ED8',
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
                            callback: function(value) {
                                return '৳' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Refresh dashboard function
        function refreshDashboard() {
            window.location.reload();
        }
    </script>
</x-main-layout>
