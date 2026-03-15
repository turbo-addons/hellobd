<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'হ্যালোবিডি নিউজ')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('frontend.partials.header')
    
    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>
    
    @include('frontend.partials.footer')
</body>
</html>
