<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\Post;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Term::where('slug', $slug)
            ->where('taxonomy', 'category')
            ->firstOrFail();

        $posts = Post::with(['user.reporter', 'terms'])
            ->where('status', 'published')
            ->whereHas('terms', function ($q) use ($category) {
                $q->where('terms.id', $category->id)
                  ->where('terms.taxonomy', 'category');
            })
            ->latest()
            ->paginate(20);

        // Breaking news for ticker
        $breaking = Post::where('status', 'published')
            ->where('is_breaking', 1)
            ->latest()
            ->take(5)
            ->get();

        return view('frontend.pages.category', compact('category', 'posts', 'breaking'));
    }
}
