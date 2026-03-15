<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Term;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $data = Cache::remember('frontend_home', 300, function () {
            return [
                'featured' => Post::with(['user.reporter', 'terms'])
                    ->where('status', 'published')
                    ->where('is_featured', 1)
                    ->latest()
                    ->take(5)
                    ->get(),
                'latest' => Post::with(['user.reporter', 'terms'])
                    ->where('status', 'published')
                    ->latest()
                    ->take(12)
                    ->get(),
                'breaking' => Post::where('status', 'published')
                    ->where('is_breaking', 1)
                    ->latest()
                    ->take(5)
                    ->get(),
                'urgent' => Post::where('status', 'published')
                    ->where('is_breaking', 1)
                    ->latest()
                    ->take(3)
                    ->get(),
                'popular' => Post::where('status', 'published')
                    ->orderBy('views', 'desc')
                    ->take(6)
                    ->get(),
                'categories' => Term::where('taxonomy', 'category')
                    ->withCount('posts')
                    ->orderBy('id')
                    ->get(),
            ];
        });

        return view('frontend.home', $data);
    }
}
