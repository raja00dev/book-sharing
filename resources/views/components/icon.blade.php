@props(['name', 'class' => 'size-5'])

<svg {{ $attributes->class($class) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($name)
        @case('home')
            <path d="m3 10 9-7 9 7" />
            <path d="M5 9v11h14V9M9 20v-6h6v6" />
            @break
        @case('search')
            <circle cx="10.8" cy="10.8" r="6.8" />
            <path d="m16 16 5 5" />
            @break
        @case('user')
            <circle cx="12" cy="8" r="3.5" />
            <path d="M5 21v-1.5a7 7 0 0 1 14 0V21" />
            @break
        @case('orders')
            <path d="M6 3.5h12v17l-6-3.5-6 3.5v-17Z" />
            <path d="M9 8h6M9 11.5h6" />
            @break
        @case('back')
            <path d="M19 12H5" />
            <path d="m12 19-7-7 7-7" />
            @break
        @case('logout')
            <path d="M10 17l5-5-5-5M15 12H3" />
            <path d="M12 3h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7" />
            @break
    @endswitch
</svg>
