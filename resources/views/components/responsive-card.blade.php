@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
    'padding' => 'p-6',
    'shadow' => 'shadow',
    'rounded' => 'rounded-lg',
    'border' => 'border border-gray-200 dark:border-gray-700',
    'background' => 'bg-white dark:bg-gray-800',
])

<div {{ $attributes->merge(['class' => "{$background} {$border} {$rounded} {$shadow} {$padding}"]) }}>
    @if($title || $subtitle || $actions)
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex-1 min-w-0">
                @if($title)
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white truncate">
                        {{ $title }}
                    </h3>
                @endif
                @if($subtitle)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 truncate">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
            @if($actions)
                <div class="ml-4 flex-shrink-0 flex space-x-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="space-y-4">
        {{ $slot }}
    </div>
</div>
