<x-main-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reliefItem->name }}</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.relief-items.edit', $reliefItem) }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.relief-items.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Relief Items
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Relief Item Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="card p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Basic Information</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Item Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefItem->name }}</dd>
                    </div>
                    @if($reliefItem->name_bn)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Bengali Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefItem->name_bn }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($reliefItem->type === 'monetary') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($reliefItem->type === 'food') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($reliefItem->type === 'medical') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @elseif($reliefItem->type === 'shelter') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                @endif">
                                {{ $reliefItem->formatted_type }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefItem->formatted_unit }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1">
                            @if($reliefItem->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Inactive
                                </span>
                            @endif
                        </dd>
                    </div>
                    @if($reliefItem->description)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefItem->description }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefItem->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $reliefItem->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Statistics -->
            <div class="card p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Statistics</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Applications</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $reliefItem->reliefApplicationItems->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Inventory Records</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $reliefItem->inventories->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Quantity Requested</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($reliefItem->reliefApplicationItems->sum('quantity_requested'), 3) }} {{ $reliefItem->formatted_unit }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Quantity Approved</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($reliefItem->reliefApplicationItems->sum('quantity_approved'), 3) }} {{ $reliefItem->formatted_unit }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            ৳{{ number_format($reliefItem->reliefApplicationItems->sum('total_amount'), 2) }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Recent Applications -->
        @if($reliefItem->reliefApplicationItems->count() > 0)
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Applications</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Applications requesting this relief item</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Organization</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantity Requested</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantity Approved</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($reliefItem->reliefApplicationItems->take(10) as $applicationItem)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $applicationItem->reliefApplication->organization_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $applicationItem->reliefApplication->subject }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($applicationItem->quantity_requested, 3) }} {{ $reliefItem->formatted_unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                @if($applicationItem->quantity_approved)
                                    {{ number_format($applicationItem->quantity_approved, 3) }} {{ $reliefItem->formatted_unit }}
                                @else
                                    <span class="text-gray-400">Not approved</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($applicationItem->reliefApplication->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($applicationItem->reliefApplication->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @endif">
                                    {{ ucfirst($applicationItem->reliefApplication->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $applicationItem->reliefApplication->date->format('M d, Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Inventory Records -->
        @if($reliefItem->inventories->count() > 0)
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Inventory Records</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Current inventory across all projects</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Current Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reserved Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Received</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Distributed</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unit Price</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($reliefItem->inventories as $inventory)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $inventory->project->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($inventory->current_stock, 3) }} {{ $reliefItem->formatted_unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($inventory->reserved_stock, 3) }} {{ $reliefItem->formatted_unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($inventory->total_received, 3) }} {{ $reliefItem->formatted_unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ number_format($inventory->total_distributed, 3) }} {{ $reliefItem->formatted_unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                @if($inventory->unit_price)
                                    ৳{{ number_format($inventory->unit_price, 2) }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</x-main-layout>
