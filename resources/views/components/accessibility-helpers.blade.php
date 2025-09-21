{{-- Accessibility Helpers Component --}}
{{-- This component provides accessibility enhancements for the application --}}

{{-- Skip to main content link for screen readers --}}
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 bg-blue-600 text-white px-4 py-2 rounded-md z-50">
    Skip to main content
</a>

{{-- Screen reader only text helper --}}
@props(['text'])

@if(isset($text))
    <span class="sr-only">{{ $text }}</span>
@endif

{{-- Focus trap for modals --}}
@props(['trap' => false])

@if($trap)
    <div 
        x-data="{ 
            trapFocus() {
                const focusableElements = this.$el.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex=\'-1\'])');
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                
                this.$el.addEventListener('keydown', (e) => {
                    if (e.key === 'Tab') {
                        if (e.shiftKey) {
                            if (document.activeElement === firstElement) {
                                lastElement.focus();
                                e.preventDefault();
                            }
                        } else {
                            if (document.activeElement === lastElement) {
                                firstElement.focus();
                                e.preventDefault();
                            }
                        }
                    }
                });
            }
        }"
        x-init="trapFocus()"
        {{ $attributes }}
    >
        {{ $slot }}
    </div>
@else
    <div {{ $attributes }}>
        {{ $slot }}
    </div>
@endif

{{-- ARIA live region for dynamic content updates --}}
<div 
    x-data="{ 
        announce(message) {
            this.$refs.announcer.textContent = message;
            setTimeout(() => {
                this.$refs.announcer.textContent = '';
            }, 1000);
        }
    }"
    x-ref="announcer"
    aria-live="polite"
    aria-atomic="true"
    class="sr-only"
></div>

{{-- High contrast mode toggle --}}
<div 
    x-data="{ 
        highContrast: localStorage.getItem('highContrast') === 'true',
        toggleHighContrast() {
            this.highContrast = !this.highContrast;
            localStorage.setItem('highContrast', this.highContrast);
            document.documentElement.classList.toggle('high-contrast', this.highContrast);
        }
    }"
    x-init="
        if (highContrast) {
            document.documentElement.classList.add('high-contrast');
        }
    "
    class="fixed bottom-4 left-4 z-50"
>
    <button 
        @click="toggleHighContrast()"
        :class="{ 'bg-yellow-500 text-black': highContrast, 'bg-gray-600 text-white': !highContrast }"
        class="p-2 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        :aria-pressed="highContrast"
        title="Toggle high contrast mode"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
    </button>
</div>

{{-- Reduced motion preference support --}}
<style>
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
    
    .high-contrast {
        --tw-bg-opacity: 1;
        --tw-text-opacity: 1;
    }
    
    .high-contrast .bg-white {
        background-color: white !important;
        color: black !important;
    }
    
    .high-contrast .bg-gray-50 {
        background-color: white !important;
        color: black !important;
    }
    
    .high-contrast .bg-gray-100 {
        background-color: #f0f0f0 !important;
        color: black !important;
    }
    
    .high-contrast .bg-gray-200 {
        background-color: #e0e0e0 !important;
        color: black !important;
    }
    
    .high-contrast .bg-gray-300 {
        background-color: #d0d0d0 !important;
        color: black !important;
    }
    
    .high-contrast .bg-gray-400 {
        background-color: #c0c0c0 !important;
        color: black !important;
    }
    
    .high-contrast .bg-gray-500 {
        background-color: #a0a0a0 !important;
        color: black !important;
    }
    
    .high-contrast .bg-gray-600 {
        background-color: #808080 !important;
        color: white !important;
    }
    
    .high-contrast .bg-gray-700 {
        background-color: #606060 !important;
        color: white !important;
    }
    
    .high-contrast .bg-gray-800 {
        background-color: #404040 !important;
        color: white !important;
    }
    
    .high-contrast .bg-gray-900 {
        background-color: #202020 !important;
        color: white !important;
    }
    
    .high-contrast .text-gray-500 {
        color: #666666 !important;
    }
    
    .high-contrast .text-gray-600 {
        color: #555555 !important;
    }
    
    .high-contrast .text-gray-700 {
        color: #444444 !important;
    }
    
    .high-contrast .text-gray-800 {
        color: #333333 !important;
    }
    
    .high-contrast .text-gray-900 {
        color: #222222 !important;
    }
    
    .high-contrast .border-gray-200 {
        border-color: #cccccc !important;
    }
    
    .high-contrast .border-gray-300 {
        border-color: #bbbbbb !important;
    }
    
    .high-contrast .border-gray-400 {
        border-color: #aaaaaa !important;
    }
    
    .high-contrast .border-gray-500 {
        border-color: #999999 !important;
    }
    
    .high-contrast .border-gray-600 {
        border-color: #888888 !important;
    }
    
    .high-contrast .border-gray-700 {
        border-color: #777777 !important;
    }
    
    .high-contrast .border-gray-800 {
        border-color: #666666 !important;
    }
    
    .high-contrast .border-gray-900 {
        border-color: #555555 !important;
    }
    
    .high-contrast .focus\:ring-blue-500:focus {
        --tw-ring-color: #0000ff !important;
    }
    
    .high-contrast .focus\:border-blue-500:focus {
        border-color: #0000ff !important;
    }
    
    .high-contrast .bg-blue-600 {
        background-color: #0000ff !important;
        color: white !important;
    }
    
    .high-contrast .bg-green-600 {
        background-color: #008000 !important;
        color: white !important;
    }
    
    .high-contrast .bg-red-600 {
        background-color: #ff0000 !important;
        color: white !important;
    }
    
    .high-contrast .bg-yellow-600 {
        background-color: #ffff00 !important;
        color: black !important;
    }
    
    .high-contrast .text-blue-600 {
        color: #0000ff !important;
    }
    
    .high-contrast .text-green-600 {
        color: #008000 !important;
    }
    
    .high-contrast .text-red-600 {
        color: #ff0000 !important;
    }
    
    .high-contrast .text-yellow-600 {
        color: #ffff00 !important;
    }
</style>
