<button {{ $attributes->merge(['type' => 'submit']) }}
    class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2"
    style="background:#0F3B38;"
    onmouseover="this.style.background='#0B2D2B'"
    onmouseout="this.style.background='#0F3B38'">
    {{ $slot }}
</button>
