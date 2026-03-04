@props(['size' => 'md'])

@php
    $variants = [
        'sm' => [
            'gap' => 'gap-2',
            'icon' => 'h-7 w-7 rounded-lg',
            'svg' => 'h-4 w-4',
            'text' => 'text-lg',
        ],
        'md' => [
            'gap' => 'gap-2.5',
            'icon' => 'h-9 w-9 rounded-xl',
            'svg' => 'h-5 w-5',
            'text' => 'text-2xl',
        ],
        'lg' => [
            'gap' => 'gap-3',
            'icon' => 'h-11 w-11 rounded-2xl',
            'svg' => 'h-6 w-6',
            'text' => 'text-3xl',
        ],
    ];

    $ui = $variants[$size] ?? $variants['md'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center ' . $ui['gap']]) }}>
    <span class="inline-flex items-center justify-center bg-gradient-to-br from-sky-500 via-cyan-500 to-emerald-500 text-white shadow-md shadow-cyan-500/30 {{ $ui['icon'] }}">
        <svg class="{{ $ui['svg'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2m0 0L7 13h10l2-8H5.4m0 0L4.2 3M7 13l-1 2h12m-9 3a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"></path>
        </svg>
    </span>
    <span class="font-black tracking-tight text-slate-900 {{ $ui['text'] }}">
        <span class="bg-gradient-to-r from-sky-600 via-cyan-500 to-emerald-500 bg-clip-text text-transparent">
            Sakha
        </span>
    </span>
</span>
