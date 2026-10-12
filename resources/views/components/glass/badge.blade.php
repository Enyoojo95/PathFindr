{{--
    <x-glass.badge color="green|blue|amber|red|purple|gray" [dot]>
        Active
    </x-glass.badge>

    dot — shows a small coloured pulse dot before the text
--}}
@props([
    'color' => 'gray',
    'dot'   => false,
])

@php
    $dotColors = [
        'green'  => '#10B981',
        'blue'   => '#2563EB',
        'amber'  => '#F59E0B',
        'red'    => '#EF4444',
        'purple' => '#7C3AED',
        'gray'   => '#9CA3AF',
    ];
    $dotColor = $dotColors[$color] ?? '#9CA3AF';
@endphp

<span {{ $attributes->merge(['class' => 'glass-badge glass-badge-' . $color]) }}>
    @if($dot)
    <span style="width:5px;height:5px;border-radius:50%;background:{{ $dotColor }};
                 display:inline-block;flex-shrink:0;"></span>
    @endif
    {{ $slot }}
</span>
