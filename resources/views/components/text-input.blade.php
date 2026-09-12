@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'rounded-lg shadow-sm text-sm'
]) !!} style="border-color:#DCEAE6; background:#FBFDFC; color:#0E2624;"
onfocus="this.style.borderColor='#1D6B63'; this.style.boxShadow='0 0 0 4px rgba(29,107,99,0.12)';"
onblur="this.style.borderColor='#DCEAE6'; this.style.boxShadow='none';">