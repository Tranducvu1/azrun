@extends('layouts.app')
@section('title', 'Tin Tức — AZRun')

@section('content')
<div class="bg-brand-black text-white py-12 md:py-16 relative overflow-hidden">
    <div class="absolute inset-0 opacity-30" style="background: radial-gradient(circle at 70% 50%, rgba(255,69,0,0.4) 0%, transparent 60%);"></div>
    <div class="max-w-7xl mx-auto px-4 relative">
        <p class="text-lime text-xs font-bold tracking-[0.2em] uppercase mb-3">Blog</p>
        <h1 class="font-display text-3xl md:text-5xl font-bold">Tin tức & Tips chạy bộ</h1>
        <p class="text-gray-500 mt-2">Kiến thức, review giày, hướng dẫn training</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12 md:py-16">
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($posts as $i => $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group bg-white rounded-2xl shadow-card overflow-hidden hover:-translate-y-1 hover:shadow-glow transition-all duration-300 {{ $i === 0 ? 'md:col-span-2 lg:col-span-2 md:grid md:grid-cols-2' : '' }}">
                <div class="overflow-hidden {{ $i === 0 ? 'md:min-h-[260px]' : 'aspect-video' }}">
                    <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
                <div class="p-5 md:p-6 flex flex-col justify-center">
                    <p class="text-accent text-[10px] font-bold tracking-widest uppercase mb-2">{{ $post->published_at->format('d M Y') }}</p>
                    <h2 class="font-display font-bold text-brand-black {{ $i === 0 ? 'text-xl md:text-2xl' : 'text-lg' }} group-hover:text-accent transition-colors line-clamp-2">{{ $post->title }}</h2>
                    <p class="text-sm text-gray-500 mt-2 line-clamp-2 flex-1">{{ $post->excerpt }}</p>
                    <span class="inline-flex items-center gap-1 mt-4 text-sm font-bold text-brand-black group-hover:text-accent transition-colors">Đọc thêm →</span>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-10 flex justify-center">{{ $posts->links() }}</div>
</div>
@endsection
