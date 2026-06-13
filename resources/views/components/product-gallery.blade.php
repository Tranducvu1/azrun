@props(['product'])

@php
    $gallery = $product->galleryImages();
    $galleryJson = json_encode($gallery, JSON_UNESCAPED_SLASHES | JSON_HEX_APOS);
    $nameJson = json_encode($product->name, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS);
@endphp

<div
    class="product-gallery"
    x-data="productGallery({!! $galleryJson !!}, {!! $nameJson !!})"
    x-init="init()"
    @keydown.window="onKey($event)"
>
    {{-- Main stage --}}
    <div class="relative bg-white rounded-3xl overflow-hidden shadow-card mb-4 group/stage"
         :class="lightbox && 'ring-2 ring-primary/30'">

        @if($product->isOnSale() && $product->discount_percent)
            <span class="absolute top-4 left-4 z-20 px-3 py-1.5 bg-primary text-white text-sm font-black rounded-full shadow-lg">
                -{{ $product->discount_percent }}%
            </span>
        @endif

        <button type="button"
                @click="openLightbox()"
                class="gallery-stage relative w-full aspect-square overflow-hidden cursor-zoom-in focus:outline-none focus-visible:ring-2 focus-visible:ring-primary"
                @mousemove="onMouseMove($event)"
                @mouseleave="zooming = false"
                aria-label="Phóng to ảnh sản phẩm">

            <template x-for="(img, idx) in images" :key="img">
                <img :src="img"
                     :alt="name + ' — ảnh ' + (idx + 1)"
                     class="absolute inset-0 w-full h-full object-cover transition-all duration-500 ease-out"
                     :class="{
                         'opacity-100 scale-100 z-10': active === idx,
                         'opacity-0 scale-[1.02] z-0': active !== idx,
                         'scale-150': active === idx && zooming
                     }"
                     :style="active === idx && zooming ? `transform-origin: ${zoomX}% ${zoomY}%` : ''"
                     loading="eager">
            </template>

            {{-- Zoom hint --}}
            <div class="absolute bottom-4 right-4 z-20 flex items-center gap-2 px-3 py-1.5 bg-black/50 backdrop-blur-sm text-white text-xs font-semibold rounded-full opacity-0 group-hover/stage:opacity-100 transition-opacity pointer-events-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                Phóng to
            </div>

            @if(count($gallery) > 1)
                <button type="button" @click.stop="prev()" class="gallery-nav gallery-nav-prev" aria-label="Ảnh trước">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" @click.stop="next()" class="gallery-nav gallery-nav-next" aria-label="Ảnh sau">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            @endif
        </button>

        @if(count($gallery) > 1)
            <div class="absolute bottom-4 left-4 z-20 px-2.5 py-1 bg-black/40 backdrop-blur text-white text-[11px] font-bold rounded-full">
                <span x-text="active + 1"></span> / <span x-text="images.length"></span>
            </div>
        @endif
    </div>

    {{-- Thumbnails --}}
    @if(count($gallery) > 1)
        <div class="flex gap-2 overflow-x-auto pb-1 gallery-thumbs-scroll snap-x">
            <template x-for="(img, idx) in images" :key="'thumb-' + img">
                <button type="button"
                        @click="go(idx)"
                        class="gallery-thumb snap-start shrink-0 rounded-xl overflow-hidden transition-all duration-300"
                        :class="active === idx ? 'ring-2 ring-primary ring-offset-2 scale-105' : 'opacity-60 hover:opacity-100'"
                        :aria-label="'Xem ảnh ' + (idx + 1)">
                    <img :src="img" :alt="name" class="w-16 h-16 md:w-20 md:h-20 object-cover" loading="lazy">
                </button>
            </template>
        </div>
    @endif

    {{-- Lightbox --}}
    <div x-show="lightbox"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="gallery-lightbox"
         style="display:none"
         @click.self="closeLightbox()"
         x-cloak>

        <button type="button" @click="closeLightbox()" class="gallery-lightbox-close" aria-label="Đóng">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        @if(count($gallery) > 1)
            <button type="button" @click="prev()" class="gallery-lightbox-nav gallery-lightbox-prev" aria-label="Ảnh trước">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" @click="next()" class="gallery-lightbox-nav gallery-lightbox-next" aria-label="Ảnh sau">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        @endif

        <div class="gallery-lightbox-stage"
             x-transition:enter="transition ease-out duration-400"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <img :src="images[active]" :alt="name" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">
            <p class="text-center text-white/70 text-sm mt-4 font-medium" x-text="name"></p>
            @if(count($gallery) > 1)
                <p class="text-center text-white/40 text-xs mt-1">
                    <span x-text="active + 1"></span> / <span x-text="images.length"></span>
                </p>
            @endif
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
function productGallery(images, name) {
    return {
        images: images || [],
        name: name || '',
        active: 0,
        lightbox: false,
        zooming: false,
        zoomX: 50,
        zoomY: 50,
        touchStartX: 0,

        init() {
            if (window.matchMedia('(max-width: 768px)').matches) {
                this.$el.querySelector('.gallery-stage')?.addEventListener('touchstart', (e) => {
                    this.touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });
                this.$el.querySelector('.gallery-stage')?.addEventListener('touchend', (e) => {
                    const diff = e.changedTouches[0].screenX - this.touchStartX;
                    if (Math.abs(diff) > 50) diff > 0 ? this.prev() : this.next();
                }, { passive: true });
            }
        },

        go(idx) {
            this.active = idx;
            this.zooming = false;
        },

        next() {
            this.active = (this.active + 1) % this.images.length;
            this.zooming = false;
        },

        prev() {
            this.active = (this.active - 1 + this.images.length) % this.images.length;
            this.zooming = false;
        },

        openLightbox() {
            this.lightbox = true;
            document.body.style.overflow = 'hidden';
        },

        closeLightbox() {
            this.lightbox = false;
            document.body.style.overflow = '';
        },

        onMouseMove(e) {
            if (window.matchMedia('(max-width: 768px)').matches) return;
            const rect = e.currentTarget.getBoundingClientRect();
            this.zoomX = ((e.clientX - rect.left) / rect.width) * 100;
            this.zoomY = ((e.clientY - rect.top) / rect.height) * 100;
            this.zooming = true;
        },

        onKey(e) {
            if (!this.lightbox) return;
            if (e.key === 'Escape') this.closeLightbox();
            if (e.key === 'ArrowRight') this.next();
            if (e.key === 'ArrowLeft') this.prev();
        },
    };
}
</script>
@endpush
@endonce
