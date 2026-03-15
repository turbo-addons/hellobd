<!DOCTYPE html>
<html lang="bn" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HelloBD News - বাংলাদেশের প্রধান সংবাদ পোর্টাল')</title>
    
    @stack('meta')
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="font-bengali bg-gray-50 dark:bg-gray-900 transition-colors">
    
    <!-- Top Bar -->
    <div class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-2 text-sm">
                <div class="flex items-center gap-4 text-gray-600 dark:text-gray-300">
                    <span>{{ \Carbon\Carbon::now()->locale('bn')->isoFormat('dddd, D MMMM, YYYY') }}</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">ই-পেপার</a>
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">আর্কাইভ</a>
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-blue-600">English</a>
                    <a href="#" class="text-red-600 hover:text-red-700">📺 YouTube</a>
                    <button id="theme-toggle" class="text-gray-600 dark:text-gray-300">
                        <span class="dark:hidden">🌙</span>
                        <span class="hidden dark:inline">☀️</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logo & Main Menu -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <a href="/" class="text-3xl font-bold text-blue-600">HelloBD News</a>
                
                <nav class="hidden lg:flex items-center gap-6">
                    <a href="/" class="text-gray-700 dark:text-gray-200 hover:text-blue-600">সর্বশেষ</a>
                    @php
                        $menuCategories = \App\Models\Term::where('taxonomy', 'category')
                            ->whereIn('slug', ['bangladesh', 'politics', 'international', 'economy', 'entertainment', 'opinion', 'sports', 'jobs', 'trending'])
                            ->orderByRaw("FIELD(slug, 'bangladesh', 'politics', 'international', 'economy', 'entertainment', 'opinion', 'sports', 'jobs', 'trending')")
                            ->get();
                    @endphp
                    @foreach($menuCategories as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" class="text-gray-700 dark:text-gray-200 hover:text-blue-600">{{ $cat->name_bn ?? $cat->name }}</a>
                    @endforeach
                </nav>

                <div class="flex items-center gap-4">
                    <form action="/search" method="GET" class="relative">
                        <input type="text" name="q" placeholder="খুঁজুন..." 
                            class="pl-4 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2">🔍</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Breaking News Ticker -->
    @if(isset($breaking) && $breaking->count() > 0)
    <div class="bg-red-600 text-white py-2">
        <div class="container mx-auto px-4 flex items-center gap-4">
            <span class="font-bold whitespace-nowrap">জরুরি</span>
            <div class="flex-1 overflow-hidden">
                <marquee behavior="scroll" direction="left" scrollamount="5">
                    @foreach($breaking as $index => $news)
                        {{ $news->title }}@if(!$loop->last) • @endif
                    @endforeach
                </marquee>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4">HelloBD News</h3>
                    <p class="text-gray-300">বাংলাদেশের প্রধান সংবাদ পোর্টাল। সত্য ও নিরপেক্ষ সংবাদ পরিবেশনে আমরা প্রতিশ্রুতিবদ্ধ।</p>
                    <div class="flex gap-4 mt-4">
                        <a href="#" class="text-blue-400 hover:text-blue-300">Facebook</a>
                        <a href="#" class="text-blue-400 hover:text-blue-300">Twitter</a>
                        <a href="#" class="text-red-400 hover:text-red-300">YouTube</a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold mb-4">গুরুত্বপূর্ণ লিংক</h4>
                    <ul class="space-y-2 text-gray-300">
                        <li><a href="#" class="hover:text-white">সম্পাদকীয় নীতি</a></li>
                        <li><a href="#" class="hover:text-white">যোগাযোগ</a></li>
                        <li><a href="#" class="hover:text-white">গোপনীয়তা নীতি</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">যোগাযোগ</h4>
                    <p class="text-gray-300">ইমেইল: info@hellobd.news</p>
                    <p class="text-gray-300">ফোন: +৮৮০ ১৭১২ ৩৪৫৬৭৮</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-4 text-center text-gray-400">
                © {{ date('Y') }} HelloBD News. সকল অধিকার সংরক্ষিত।
            </div>
        </div>
    </footer>

    <script>
        // Dark mode toggle
        const toggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        
        toggle?.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });
        
        if (localStorage.getItem('theme') === 'dark') {
            html.classList.add('dark');
        }
    </script>

    @stack('scripts')
</body>
</html>
