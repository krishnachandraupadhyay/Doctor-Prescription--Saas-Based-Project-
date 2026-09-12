@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[11px] font-semibold uppercase tracking-wide mb-1']) }}
       style="color:#0F3B38;">
    {{ $value ?? $slot }}
</label>