@extends('frontend.layouts.master')

@section('title', 'হ্যালোবিডি নিউজ - প্রচ্ছদ')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        @if($breakingNews->count())
        <div class="bg-red-600 text-white p-4 mb-6 rounded">
            <h2 class="text-xl font-bold mb-2">জরুরি খবর</h2>
            @foreach($breakingNews as $news)
            <a href="/post/{{ $news->slug }}" class="block hover:underline">{{ $news->title }}</a>
            @endforeach
        </div>
        @endif

        <section class="mb-8">
            <h2 class="text-2xl font-bold mb-4 border-b-2 border-red-600 pb-2">সর্বশেষ সংবাদ</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($latestPosts as $post)
                <article class="bg-white rounded shadow overflow-hidden">
                    @if($post->featured_image)
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">
                            <a href="/post/{{ $post->slug }}" class="hover:text-red-600">{{ $post->title }}</a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($post->excerpt, 100) }}</p>
                        <div class="text-xs text-gray-500">
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                            @if($post->reporter)
                            <span class="ml-2">• {{ $post->reporter->display_name }}</span>
                            @endif
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </section>

        @foreach($categorySections as $categorySlug => $section)
        <section class="mb-8">
            <h2 class="text-2xl font-bold mb-4 border-b-2 border-red-600 pb-2">{{ $section['name'] }}</h2>
            <div class="space-y-4">
                @foreach($section['posts'] as $post)
                <article class="bg-white rounded shadow p-4 flex gap-4">
                    @if($post->featured_image)
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-32 h-24 object-cover rounded">
                    @endif
                    <div class="flex-1">
                        <h3 class="font-bold mb-2">
                            <a href="/post/{{ $post->slug }}" class="hover:text-red-600">{{ $post->title }}</a>
                        </h3>
                        <div class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                    </div>
                </article>
                @endforeach
            </div>
        </section>
        @endforeach
    </div>

    <aside class="lg:col-span-1">
        <div class="bg-white rounded shadow p-4 mb-6">
            <h3 class="font-bold text-lg mb-4 border-b pb-2">জনপ্রিয় সংবাদ</h3>
            <div class="space-y-3">
                @foreach($popularPosts as $post)
                <article>
                    <h4 class="text-sm font-semibold">
                        <a href="/post/{{ $post->slug }}" class="hover:text-red-600">{{ $post->title }}</a>
                    </h4>
                    <div class="text-xs text-gray-500 mt-1">{{ $post->created_at->diffForHumans() }}</div>
                </article>
                @endforeach
            </div>
        </div>

        @if($sidebarAd)
        <div class="bg-gray-100 rounded p-4 mb-6 text-center">
            {!! $sidebarAd->content !!}
        </div>
        @endif
    </aside>
</div>
@endsection
