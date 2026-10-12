{{--
    <x-glass.button [variant="primary|danger|default"] [type="button"] [href] [size="sm|md"] [icon="fas fa-save"]>
        Label
    </x-glass.button>

    When href is provided renders an <a>, otherwise <button>.
--}}
@props([
    'variant' => 'default',
    'type'    => 'button',
    'href'    => null,
    'icon'    => null,
    'size'    => 'md',
])

@php
    $variantClass = match($variant) {
        'primary' => 'glass-btn-primary',
        'danger'  => 'glass-btn-danger',
        default   => '',
    };
    $sizeStyle = $size === 'sm' ? 'padding:7px 14px;font-size:0.78rem;' : '';
@endphp

@if($href)
<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'glass-btn ' . $variantClass]) }}
    style="{{ $sizeStyle }}"
>
    @if($icon)<i class="{{ $icon }}" style="font-size:0.8em;pointer-events:none;"></i>@endif
    {{ $slot }}
</a>
@else
<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'glass-btn ' . $variantClass]) }}
    style="{{ $sizeStyle }}"
>
    @if($icon)<i class="{{ $icon }}" style="font-size:0.8em;pointer-events:none;"></i>@endif
    {{ $slot }}
</button>
@endif
