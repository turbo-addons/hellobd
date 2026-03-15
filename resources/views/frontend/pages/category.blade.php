@extends('frontend.layouts.app')

@section('title', ($category->name_bn ?? $category->name) . ' - হ্যালোবিডি নিউজ')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold border-b-2 border-red-600 pb-2">{{ $category->name_bn ?? $category->name }}</h1>
    @if($posts->total() > 0)
    <p class="text-gray-600 mt-2">{{ $posts->total() }} টি সংবাদ পাওয়া গেছে</p>
    @endif
</div>

@if($posts->count() > 0)
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($posts as $post)
    <article class="bg-white rounded shadow overflow-hidden hover:shadow-lg transition">
        @if($post->featured_image)
        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
        @endif
        <div class="p-4">
            <h3 class="font-bold text-lg mb-2">
                <a href="{{ route('news.show', $post->slug) }}" class="hover:text-red-600">{{ $post->title }}</a>
            </h3>
            <p class="text-gray-600 text-sm mb-2">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}</p>
            <div class="flex justify-between items-center text-xs text-gray-500">
                <span>{{ $post->created_at->diffForHumans() }}</span>
                @if($post->views > 0)
                <span>👁 {{ number_format($post->views) }}</span>
                @endif
            </div>
        </div>
    </article>
    @endforeach
</div>

<div class="mt-6">
    {{ $posts->links() }}
</div>
@else
<div class="bg-yellow-50 border border-yellow-200 rounded p-6 text-center">
    <p class="text-gray-700 text-lg">এই বিভাগে এখনো কোনো সংবাদ নেই।</p>
    <a href="/" class="text-red-600 hover:underline mt-2 inline-block">হোমপেজে ফিরে যান</a>
</div>
@endif
@endsection
