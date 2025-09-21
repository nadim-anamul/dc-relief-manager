@props([
    'headers' => [],
    'data' => [],
    'emptyMessage' => 'No data available',
    'loading' => false,
    'striped' => true,
    'hover' => true,
])

<div class="overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            @if(!empty($headers))
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        @foreach($headers as $header)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @if($loading)
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <x-loading-spinner size="lg" color="blue" />
                                <p class="text-sm text-gray-500 dark:text-gray-400">Loading...</p>
                            </div>
                        </td>
                    </tr>
                @elseif(empty($data))
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $emptyMessage }}</p>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach($data as $index => $row)
                        <tr class="{{ $striped && $index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-800' : '' }} {{ $hover ? 'hover:bg-gray-100 dark:hover:bg-gray-700' : '' }} transition-colors duration-150">
                            {{ $row }}
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
