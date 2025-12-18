{{-- resources/views/components/application-logo.blade.php --}}
@props(['class' => 'w-8 h-8'])

<img src="{{ asset('images/logo.png') }}" 
     alt="Memo Potret Logo" 
     class="{{ $class }}" />