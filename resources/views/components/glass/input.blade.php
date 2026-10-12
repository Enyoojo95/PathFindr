{{--
    <x-glass.input
        label="Email Address"
        name="email"
        type="email"
        :value="old('email')"
        icon="fas fa-envelope"
        placeholder="you@example.com"
        :error="$errors->first('email')"
    />
--}}
@props([
    'label'       => null,
    'name'        => '',
    'type'        => 'text',
    'value'       => '',
    'placeholder' => '',
    'icon'        => null,
    'error'       => null,
    'rows'        => null,   {{-- if set, renders a <textarea> --}}
])

<div>
    @if($label)
    <label for="{{ $name }}" class="glass-label">{{ $label }}</label>
    @endif

    <div style="position:relative;">
        @if($icon)
        <i class="{{ $icon }}"
           style="position:absolute;left:13px;top:50%;transform:translateY(-50%);
                  color:var(--text-dim);font-size:0.78rem;pointer-events:none;z-index:1;
                  {{ $rows ? 'top:14px;transform:none;' : '' }}"></i>
        @endif

        @if($rows)
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'glass-input' . ($icon ? ' glass-input-icon' : '') . ($error ? ' !border-red-500/60' : '')]) }}
            style="resize:vertical;"
        >{{ $value }}</textarea>
        @else
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge(['class' => 'glass-input' . ($icon ? ' glass-input-icon' : '') . ($error ? ' !border-red-500/60' : '')]) }}
        />
        @endif
    </div>

    @if($error)
    <p style="margin-top:5px;font-size:0.72rem;color:#FCA5A5;display:flex;align-items:center;gap:4px;">
        <i class="fas fa-exclamation-circle" style="font-size:0.65rem;"></i>
        {{ $error }}
    </p>
    @endif
</div>
