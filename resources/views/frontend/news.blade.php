@extends('frontend.layouts.app')

@section('title', $post->title . ' - HelloBD News')

@push('meta')
<meta name="description" content="{{ Str::limit(strip_tags($post->content), 160) }}">
<meta property="og:title" content="{{ $post->title }}">
<meta property="og:description" content="{{ Str::limit(strip_tags($post->content), 160) }}">
<meta property="og:image" content="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : '' }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="article">
<meta name="twitter:card" content="summary_large_image">

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "{{ $post->title }}",
  "image": "{{ $post->featured_image ? asset('storage/' . $post->featured_image) : '' }}",
  "datePublished": "{{ $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "{{ $post->user->reporter?->display_name ?? $post->user->name }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "HelloBD News",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  },
  "description": "{{ Str::limit(strip_tags($post->content), 160) }}"
}
</script>
@endpush

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <article class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        
        <div class="mb-4">
            <a href="/category/{{ $post->categories->first()?->slug }}" class="text-blue-600 dark:text-blue-400 text-sm font-semibold">
                {{ $post->categories->first()?->name ?? 'সাধারণ' }}
            </a>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>

        <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <span>{{ $post->user->reporter?->display_name ?? $post->user->name }}</span>
            <span>{{ $post->created_at->locale('bn')->isoFormat('D MMMM, YYYY') }}</span>
            <span>{{ number_format($post->views) }} বার পড়া হয়েছে</span>
        </div>

        @if($post->featured_image)
        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" 
            class="w-full rounded-lg mb-6">
        @endif

        <div class="prose prose-lg max-w-none dark:prose-invert text-gray-800 dark:text-gray-200">
            {!! $post->content !!}
        </div>

        @if($post->tags->count() > 0)
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap gap-2">
                @foreach($post->tags as $tag)
                <a href="/tag/{{ $tag->slug }}" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm hover:bg-blue-100 dark:hover:bg-blue-900">
                    #{{ $tag->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($related->count() > 0)
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">সম্পর্কিত সংবাদ</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($related as $item)
                <a href="/news/{{ $item->slug }}" class="flex gap-3 group">
                    @if($item->featured_image)
                    <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" 
                        class="w-24 h-20 object-cover rounded">
                    @endif
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm text-gray-900 dark:text-white group-hover:text-blue-600">{{ Str::limit($item->title, 60) }}</h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </article>

    <aside class="space-y-6">
        @include('frontend.partials.sidebar')
    </aside>

</div>
@endsection
