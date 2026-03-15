<header class="bg-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4">
            <div class="text-2xl font-bold text-red-600">হ্যালোবিডি নিউজ</div>
            <nav class="hidden md:flex space-x-6">
                <a href="/" class="text-gray-700 hover:text-red-600">প্রচ্ছদ</a>
                <a href="/category/latest" class="text-gray-700 hover:text-red-600">সর্বশেষ</a>
                @foreach($categories ?? [] as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="text-gray-700 hover:text-red-600">{{ $category->name_bn ?? $category->name }}</a>
                @endforeach
            </nav>
        </div>
    </div>
</header>
