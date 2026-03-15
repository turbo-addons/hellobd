@extends('frontend.layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Main Content (2 cols) -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Featured News -->
        <section>
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">প্রধান সংবাদ</h2>
            <div class="grid gap-4">
                @foreach($featured->take(5) as $post)
                <a href="/news/{{ $post->slug }}" class="group bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                    <div class="flex gap-4 p-4">
                        @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" 
                            class="w-32 h-24 object-cover rounded">
                        @endif
                        <div class="flex-1">
                            <span class="text-xs text-blue-600 dark:text-blue-400">{{ $post->categories->first()?->name ?? 'সাধারণ' }}</span>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white group-hover:text-blue-600 mt-1">{{ $post->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2 line-clamp-2">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $post->user->reporter?->display_name ?? $post->user->name }}</span>
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>

        <!-- Latest News Grid -->
        <section>
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">সর্বশেষ সংবাদ</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($latest->take(12) as $post)
                <a href="/news/{{ $post->slug }}" class="group bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" 
                        class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4">
                        <span class="text-xs text-blue-600 dark:text-blue-400">{{ $post->categories->first()?->name ?? 'সাধারণ' }}</span>
                        <h3 class="font-bold text-gray-900 dark:text-white group-hover:text-blue-600 mt-2">{{ $post->title }}</h3>
                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-500 dark:text-gray-400">
                            <span>{{ $post->user->reporter?->display_name ?? $post->user->name }}</span>
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>

    </div>

    <!-- Sidebar -->
    <aside class="space-y-6">
        
        <!-- Urgent News -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="font-bold text-lg mb-4 text-red-600 flex items-center gap-2">
                <span class="inline-block w-2 h-2 bg-red-600 rounded-full animate-pulse"></span>
                জরুরি সংবাদ
            </h3>
            <div class="space-y-3">
                @foreach($urgent as $post)
                <a href="/news/{{ $post->slug }}" class="block hover:text-blue-600 dark:text-gray-200">
                    <h4 class="font-semibold text-sm">{{ $post->title }}</h4>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Popular Posts -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">বহুল আলোচিত</h3>
            <div class="space-y-4">
                @foreach($popular as $post)
                <a href="/news/{{ $post->slug }}" class="flex gap-3 group">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" 
                        class="w-20 h-16 object-cover rounded">
                    @endif
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm text-gray-900 dark:text-white group-hover:text-blue-600">{{ Str::limit($post->title, 60) }}</h4>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($post->views) }}+ বার পড়া</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Advertisement -->
        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">বিজ্ঞাপন</p>
            <div class="bg-gray-200 dark:bg-gray-600 h-64 flex items-center justify-center text-gray-400">
                ৩০০ x ২৫০
            </div>
        </div>

        <!-- Poll -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">📊 জনমত জরিপ</h3>
            <p class="text-sm mb-4 text-gray-700 dark:text-gray-300">আগামী নির্বাচনে কোন দলকে ভোট দেবেন?</p>
            <form class="space-y-2">
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="radio" name="poll" value="1" class="text-blue-600">
                    আওয়ামী লীগ
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="radio" name="poll" value="2" class="text-blue-600">
                    বিএনপি
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="radio" name="poll" value="3" class="text-blue-600">
                    জাতীয় পার্টি
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="radio" name="poll" value="4" class="text-blue-600">
                    অন্যান্য
                </label>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 mt-4">ভোট দিন</button>
            </form>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">মোট ভোট: ১২,৩৪৫</p>
        </div>

        <!-- Live Video -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <h3 class="font-bold text-lg mb-4 text-red-600 flex items-center gap-2">
                <span class="inline-block w-2 h-2 bg-red-600 rounded-full animate-pulse"></span>
                লাইভ ভিডিও
            </h3>
            <div class="bg-black rounded-lg overflow-hidden">
                <div class="aspect-video flex items-center justify-center text-white text-4xl">▶️</div>
            </div>
            <div class="mt-3">
                <h4 class="font-semibold text-sm text-gray-900 dark:text-white">জাতীয় সংসদের বিশেষ অধিবেশন</h4>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">প্রধানমন্ত্রীর গুরুত্বপূর্ণ ভাষণ সরাসরি</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs text-red-600 font-bold">🔴 লাইভ</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">১,২৩৪ দর্শক</span>
                </div>
            </div>
        </div>

    </aside>

</div>
@endsection

@push('styles')
<style>
    @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        animation: marquee 30s linear infinite;
    }
</style>
@endpush
