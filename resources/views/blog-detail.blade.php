@extends('layouts.app')
@section('title', $post->title . ' — AZRun')

@section('content')
<article>
    <div class="relative bg-brand-black text-white">
        <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="w-full h-64 md:h-[480px] object-cover opacity-60">
        <div class="absolute inset-0 hero-gradient"></div>
        <div class="absolute inset-0 flex items-end">
            <div class="max-w-4xl mx-auto px-4 pb-10 md:pb-16 w-full">
                <nav class="text-sm text-gray-400 mb-4">
                    <a href="/" class="hover:text-white transition-colors">Trang chủ</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog</a>
                </nav>
                <p class="text-lime text-xs font-bold tracking-widest uppercase mb-3">{{ $post->published_at->format('d F Y') }}</p>
                <h1 class="font-display text-3xl md:text-5xl font-bold leading-tight max-w-3xl">{{ $post->title }}</h1>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-10 md:py-16">
        <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed">
            {!! $post->content !!}
        </div>

        <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand-black hover:text-accent transition-colors">
                ← Quay lại blog
            </a>
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-brand-black text-white font-bold rounded-2xl hover:bg-accent transition-colors">
                Mua sắm ngay
            </a>
        </div>
    </div>
</article>
@endsection
