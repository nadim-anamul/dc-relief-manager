@props([
    'title' => 'Success!',
    'message' => null,
    'dismissible' => true,
])

<x-error-alert 
    type="success" 
    :title="$title" 
    :message="$message" 
    :dismissible="$dismissible"
>
    {{ $slot }}
</x-error-alert>
