@props(['category' => null, 'size' => 16, 'round' => null])

@php
    // Icône de catégorie (App\Models\Category::ICONS). Avec :round="28", dans une pastille ronde.
    $d = $category ? $category->iconPath() : \App\Models\Category::DEFAULT_ICON;
@endphp

@if($round)
    <span {{ $attributes->merge(['class' => 'te-cat-pill']) }} style="width:{{ $round }}px;height:{{ $round }}px;" aria-hidden="true">
        <svg width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/></svg>
    </span>
@else
    <svg width="{{ $size }}" height="{{ $size }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true" {{ $attributes->merge(['class' => 'te-cat-ico']) }}><path stroke-linecap="round" stroke-linejoin="round" d="{{ $d }}"/></svg>
@endif

@once
    <style>
        .te-cat-pill { display:inline-flex; align-items:center; justify-content:center; flex-shrink:0; border-radius:50%; background:var(--te-primary-soft); color:var(--te-primary-fg); }
        .te-cat-ico { flex-shrink:0; }
    </style>
@endonce
