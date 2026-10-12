{{--
    <x-glass.card [static] [class="..."] [padding="p-6"] [tag="div"]>
        slot content
    </x-glass.card>

    static   — disables hover lift/glow (use for layout containers)
    padding  — Tailwind padding class, default p-6
    tag      — HTML tag, default div
--}}
@props([
    'static'  => false,
    'padding' => 'p-6',
    'tag'     => 'div',
])

<{{ $tag }}
    {{ $attributes->merge([
        'class' => 'glass-card ' . ($static ? 'static' : '') . ' ' . $padding,
    ]) }}
>
    {{ $slot }}
</{{ $tag }}>
