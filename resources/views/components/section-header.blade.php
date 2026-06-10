@props(['title', 'subtitle' => null, 'link' => null, 'linkText' => 'Xem tất cả', 'dark' => false])

<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
    <div>
        @if($subtitle)
            <p class="text-xs font-bold tracking-[0.2em] uppercase mb-2 {{ $dark ? 'text-accent-light' : 'text-accent' }}">{{ $subtitle }}</p>
        @endif
        <h2 class="font-display text-2xl md:text-3xl lg:text-4xl font-bold tracking-tight {{ $dark ? 'text-white' : 'text-brand-black' }}">{{ $title }}</h2>
    </div>
    @if($link)
        <a href="{{ $link }}" class="group inline-flex items-center gap-2 text-sm font-semibold {{ $dark ? 'text-white/80 hover:text-white' : 'text-brand-black hover:text-accent' }} transition-colors shrink-0">
            {{ $linkText }}
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    @endif
</div>
