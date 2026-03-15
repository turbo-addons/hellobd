<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function show($slug)
    {
        $post = Post::with(['user.reporter', 'categories', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $post->increment('views');

        $related = Cache::remember("related_posts_{$post->id}", 300, function () use ($post) {
            return Post::where('status', 'published')
                ->where('id', '!=', $post->id)
                ->whereHas('categories', function ($q) use ($post) {
                    $q->whereIn('id', $post->categories->pluck('id'));
                })
                ->latest()
                ->take(6)
                ->get();
        });

        return view('frontend.news', compact('post', 'related'));
    }

    public function showById($category, $id)
    {
        $post = Post::with(['user.reporter', 'terms'])
            ->where('id', $id)
            ->where('status', 'published')
            ->firstOrFail();

        $post->increment('views');

        $related = Cache::remember("related_posts_{$post->id}", 300, function () use ($post) {
            return Post::where('status', 'published')
                ->where('id', '!=', $post->id)
                ->whereHas('terms', function ($q) use ($post) {
                    $q->whereIn('terms.id', $post->terms->pluck('id'));
                })
                ->latest()
                ->take(6)
                ->get();
        });

        return view('frontend.news', compact('post', 'related'));
    }
}
