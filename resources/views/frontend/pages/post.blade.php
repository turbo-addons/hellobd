@extends('frontend.layouts.master')

@section('title', $post->seo_title ?? $post->title)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <article class="lg:col-span-2 bg-white rounded shadow p-6">
        @if($post->post_type_meta['breaking'] ?? false)
        <span class="inline-block bg-red-600 text-white px-3 py-1 text-sm font-bold mb-4">জরুরি</span>
        @endif
        
        <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
        
        <div class="flex items-center text-sm text-gray-600 mb-6 space-x-4">
            <span>{{ $post->created_at->format('d F Y, h:i A') }}</span>
            @if($post->reporter)
            <span>• {{ $post->reporter->display_name }}</span>
            @endif
            @if($post->category)
            <span>• <a href="/category/{{ $post->category->slug }}" class="text-red-600 hover:underline">{{ $post->category->name }}</a></span>
            @endif
        </div>

        @if($post->featured_image)
        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full rounded mb-6">
        @endif

        <div class="prose max-w-none">
            {!! $post->content !!}
        </div>

        @if($post->tags->count())
        <div class="mt-6 pt-6 border-t">
            <div class="flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                <a href="/tag/{{ $tag->slug }}" class="bg-gray-200 px-3 py-1 rounded text-sm hover:bg-gray-300">{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
        @endif
    </article>

    <aside class="lg:col-span-1">
        <div class="bg-white rounded shadow p-4 mb-6">
            <h3 class="font-bold text-lg mb-4 border-b pb-2">সম্পর্কিত সংবাদ</h3>
            <div class="space-y-3">
                @foreach($relatedPosts as $related)
                <article>
                    <h4 class="text-sm font-semibold">
                        <a href="/post/{{ $related->slug }}" class="hover:text-red-600">{{ $related->title }}</a>
                    </h4>
                </article>
                @endforeach
            </div>
        </div>
    </aside>
</div>

{!! $post->article_schema !!}
@endsection
