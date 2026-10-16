@props(['level' => null, 'size' => 16, 'round' => null])

@php
    // Icône du niveau d'études (App\Support\Education::ICONS). Avec :round="28", dans une pastille ronde.
    $d = \App\Support\Education::icon($level);
@endphp

@if($round)
    <span {{ $attributes->merge(['class' => 'te-cat-pill']) }} style="width:{{ $round }}px;height:{{ $round }}px;" aria-hidden="true">
        <svg width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/></svg>
    </span>
@else
    <svg width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" {{ $attributes->merge(['class' => 'te-edu-ico']) }} style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/></svg>
@endif
