@props(['company', 'size' => 56])

@php
    // 1. Logo du dépôt (« images/… », fichier public, fond et coins arrondis déjà dessinés) ;
    // 2. logo téléversé, s'il existe réellement dans le storage ;
    // 3. sinon logo SVG généré : initiales (2 lettres au plus) sur une couleur dérivée du nom.
    $name     = $company->name ?? 'Entreprise';
    $path     = $company->logo_path ?? null;
    $isPublic = $path && str_starts_with($path, 'images/') && is_file(public_path($path));
    $hasLogo  = !$isPublic && $path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path);
    $words    = array_values(array_filter(preg_split('/\s+/', trim(preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $name)))));
    $initials = mb_strtoupper(mb_substr($words[0] ?? 'E', 0, 1) . mb_substr($words[1] ?? '', 0, 1));
    $tones    = ['primary', 'teal', 'violet', 'info', 'success', 'warning'];
    $tone     = $tones[abs(crc32($name)) % count($tones)];
    $radius   = max(6, round($size / 5));
@endphp

@if($isPublic)
    <img src="{{ asset($path) }}" alt="Logo de {{ $name }}"
         width="{{ $size }}" height="{{ $size }}" loading="lazy"
         {{ $attributes->merge(['class' => 'te-co-logo-file']) }}
         style="width:{{ $size }}px;height:{{ $size }}px;">
@elseif($hasLogo)
    <img src="{{ asset('storage/' . $path) }}" alt="Logo de {{ $name }}"
         width="{{ $size }}" height="{{ $size }}" loading="lazy"
         {{ $attributes->merge(['class' => 'te-co-logo']) }}
         style="width:{{ $size }}px;height:{{ $size }}px;border-radius:{{ $radius }}px;">
@else
    <svg width="{{ $size }}" height="{{ $size }}" viewBox="0 0 40 40" role="img" aria-label="Logo de {{ $name }}"
         {{ $attributes->merge(['class' => 'te-co-logo-svg tone-' . $tone]) }}>
        <rect width="40" height="40" rx="{{ round($radius * 40 / $size, 1) }}" class="te-co-logo-bg"/>
        <text x="20" y="20" dy=".35em" text-anchor="middle" class="te-co-logo-txt"
              font-size="{{ mb_strlen($initials) > 1 ? 15 : 18 }}">{{ $initials }}</text>
    </svg>
@endif

@once
    <style>
        .te-co-logo { display:inline-block; flex-shrink:0; object-fit:contain; background:var(--te-logo-bg); border:1px solid var(--te-border); padding:4px; }
        .te-co-logo-file { display:inline-block; flex-shrink:0; object-fit:contain; } /* ni marge ni bordure : le fichier a son propre fond */
        .te-co-logo-svg { display:inline-block; flex-shrink:0; }
        .te-co-logo-svg .te-co-logo-txt { font-family:var(--te-font); font-weight:800; letter-spacing:-.02em; }
        .te-co-logo-svg.tone-primary .te-co-logo-bg { fill:var(--te-primary-soft); } .te-co-logo-svg.tone-primary .te-co-logo-txt { fill:var(--te-primary-fg); }
        .te-co-logo-svg.tone-teal    .te-co-logo-bg { fill:var(--te-teal-soft); }    .te-co-logo-svg.tone-teal    .te-co-logo-txt { fill:var(--te-teal-fg); }
        .te-co-logo-svg.tone-violet  .te-co-logo-bg { fill:var(--te-violet-soft); }  .te-co-logo-svg.tone-violet  .te-co-logo-txt { fill:var(--te-violet-fg); }
        .te-co-logo-svg.tone-info    .te-co-logo-bg { fill:var(--te-info-soft); }    .te-co-logo-svg.tone-info    .te-co-logo-txt { fill:var(--te-info-fg); }
        .te-co-logo-svg.tone-success .te-co-logo-bg { fill:var(--te-success-soft); } .te-co-logo-svg.tone-success .te-co-logo-txt { fill:var(--te-success-fg); }
        .te-co-logo-svg.tone-warning .te-co-logo-bg { fill:var(--te-warning-soft); } .te-co-logo-svg.tone-warning .te-co-logo-txt { fill:var(--te-warning-fg); }
    </style>
@endonce
