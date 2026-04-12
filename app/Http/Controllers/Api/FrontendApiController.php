<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Term;
use App\Models\TermMenuOrder;
use App\Models\MainMenuOrder;
use App\Models\Reporter;
use App\Models\Question;
use App\Models\Vote;
use App\Models\GeneralSetting;
use App\Models\Subscriber;
use App\Models\Advertisement;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FrontendApiController extends Controller
{
    // public function home()
    // {
    //     $data = Cache::remember('api_home', 300, function () {
    //         return [
    //             'featured' => Post::with(['user', 'categories', 'media'])
    //                 ->where('status', 'published')
    //                 // ->whereJsonContains('post_type_meta->is_featured', true)
    //                 ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_featured' = 'true'")
    //                 ->latestPublished()
    //                 ->take(4)
    //                 ->get(),
    //             'latest' => Post::with(['user', 'categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->latestPublished()
    //                 ->take(12)
    //                 ->get(),
    //             'breaking' => Post::with(['user', 'categories', 'media'])
    //                 ->where('status', 'published')
    //                 // ->whereJsonContains('post_type_meta->is_breaking', true)
    //                  ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_breaking' = 'true'")
    //                 ->latestPublished()
    //                 ->take(5)
    //                 ->get(),
    //             // 'urgent' => Post::where('status', 'published')
    //             //     ->latest()
    //             //     ->take(3)
    //             //     ->get(),
    //             'popular' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->orderBy('views', 'desc')
    //                 ->take(20)
    //                 ->get(),

    //             'world_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     $q->whereRaw('LOWER(name) = ?', ['international']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(9)
    //                 ->get(),

    //             'bangladeshi_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'bangladesh');
    //                     $q->whereRaw('LOWER(name) = ?', ['bangladesh']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'politics_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'politics');
    //                     $q->whereRaw('LOWER(name) = ?', ['politics']);
                        
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'crime_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'crime');
    //                      $q->whereRaw('LOWER(name) = ?', ['crime']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'mixed_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'mix');
    //                      $q->whereRaw('LOWER(name) = ?', ['mix']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(20)
    //                 ->get(),

    //             'economy_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'economy');
    //                      $q->whereRaw('LOWER(name) = ?', ['economy']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'science_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'science');
    //                      $q->whereRaw('LOWER(name) = ?', ['science']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'technology_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'technology');
    //                      $q->whereRaw('LOWER(name) = ?', ['technology']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'sports_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'sports');
    //                      $q->whereRaw('LOWER(name) = ?', ['sports']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(4)
    //                 ->get(),

    //             'entertainment_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'entertainment');
    //                      $q->whereRaw('LOWER(name) = ?', ['entertainment']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(4)
    //                 ->get(),

    //             'country_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'country');
    //                      $q->whereRaw('LOWER(name) = ?', ['country']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'engineering_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'engineering');
    //                      $q->whereRaw('LOWER(name) = ?', ['engineering']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'health_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'health');
    //                      $q->whereRaw('LOWER(name) = ?', ['health']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'success_storys_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'success-story');
    //                      $q->whereRaw('LOWER(name) = ?', ['success-story']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'lifestyle_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'lifestyle');
    //                      $q->whereRaw('LOWER(name) = ?', ['lifestyle']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(4)
    //                 ->get(),

    //             'multimedia_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'multimedia');
    //                      $q->whereRaw('LOWER(name) = ?', ['multimedia']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(20)
    //                 ->get(),

    //             'education_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'education');
    //                      $q->whereRaw('LOWER(name) = ?', ['education']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'environment_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'environment');
    //                      $q->whereRaw('LOWER(name) = ?', ['environment']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'interview_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'interview');
    //                      $q->whereRaw('LOWER(name) = ?', ['interview']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'corporate_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'corporate-news');
    //                      $q->whereRaw('LOWER(name) = ?', ['corporate-news']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(3)
    //                 ->get(),

    //             'photo_feature_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'photo-feature');
    //                      $q->whereRaw('LOWER(name) = ?', ['photo-feature']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(20)
    //                 ->get(),

    //             'opinion_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'opinion');
    //                      $q->whereRaw('LOWER(name) = ?', ['opinion']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(4)
    //                 ->get(),

    //             'literature_popular_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'literature');
    //                      $q->whereRaw('LOWER(name) = ?', ['literature']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(6)
    //                 ->get(),
                    
    //             'top_post_news' => Post::with(['categories', 'media'])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     // $q->where('name', 'top-post');
    //                      $q->whereRaw('LOWER(name) = ?', ['top-post']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(12)
    //                 ->get(),
    //         ];
    //     });

    //     return response()->json($data);
    // }
    
    // private function formatPostData($posts)
    // {
    //     return $posts->map(function($post) {
    //         $media = $post->media->first();
    //         return [
    //             'id' => $post->id,
    //             'title' => $post->title,
    //             'slug' => $post->slug,
    //             'excerpt' => $post->excerpt,
    //             'published_at' => $post->published_at,
    //             'original_url' => $media ? ($media->disk === 'r2' 
    //                 ? config('filesystems.disks.r2.url') . '/' . $media->id . '/' . $media->file_name
    //                 : asset('storage/' . $media->id . '/' . $media->file_name)) : null
    //         ];
    //     });
    // }

    // public function home()
    // {
    //     $data = Cache::remember('api_home', 300, function () {
    //         $selectFields = ['id', 'title', 'slug', 'excerpt', 'published_at'];
    //         $mediaQuery = function($query) {
    //             $query->select('id', 'model_id', 'file_name', 'disk');
    //         };

    //         return [
    //             'featured' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_featured' = 'true'")
    //                     ->latestPublished()
    //                     ->take(4)
    //                     ->get()
    //             ),
    //             'latest' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->latestPublished()
    //                     ->take(12)
    //                     ->get()
    //             ),
    //             'breaking' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_breaking' = 'true'")
    //                     ->latestPublished()
    //                     ->take(5)
    //                     ->get()
    //             ),
    //             'popular' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->orderBy('views', 'desc')
    //                     ->take(20)
    //                     ->get()
    //             ),
    //             'world_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['international']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(9)
    //                     ->get()
    //             ),
    //             'bangladeshi_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['bangladesh']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'politics_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['politics']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'crime_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['crime']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'mixed_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['mix']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(20)
    //                     ->get()
    //             ),
    //             'economy_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['economy']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'science_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['science']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'technology_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['technology']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'sports_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['sports']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(4)
    //                     ->get()
    //             ),
    //             'entertainment_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['entertainment']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(4)
    //                     ->get()
    //             ),
    //             'country_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['country']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'engineering_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['engineering']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'health_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['health']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'success_storys_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['success-story']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'lifestyle_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['lifestyle']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(4)
    //                     ->get()
    //             ),
    //             'multimedia_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['multimedia']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(20)
    //                     ->get()
    //             ),
    //             'education_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['education']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'environment_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['environment']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'interview_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['interview']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'corporate_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['corporate-news']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(3)
    //                     ->get()
    //             ),
    //             'photo_feature_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['photo-feature']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(20)
    //                     ->get()
    //             ),
    //             'opinion_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['opinion']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(4)
    //                     ->get()
    //             ),
    //             'literature_popular_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['literature']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(6)
    //                     ->get()
    //             ),
    //             'top_post_news' => $this->formatPostData(
    //                 Post::select($selectFields)
    //                     ->with(['media' => $mediaQuery])
    //                     ->where('status', 'published')
    //                     ->whereHas('categories', function ($q) {
    //                         $q->whereRaw('LOWER(name) = ?', ['top-post']);
    //                     })
    //                     ->latestPublished()
    //                     ->take(12)
    //                     ->get()
    //             ),
    //         ];
    //     });

    //     return response()->json($data);
    // }
    
    // public function homeOne()
    // {
    //     $data = Cache::remember('api_home_one', 300, function () {
    //         return [
    //             'featured' => Post::select('id', 'title', 'slug', 'excerpt', 'published_at')
    //                 ->with(['media' => function($query) {
    //                     $query->select('id', 'model_id', 'file_name', 'disk');
    //                 }])
    //                 ->where('status', 'published')
    //                 ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_featured' = 'true'")
    //                 ->latestPublished()
    //                 ->take(4)
    //                 ->get()
    //                 ->map(function($post) {
    //                     $media = $post->media->first();
    //                     return [
    //                         'id' => $post->id,
    //                         'title' => $post->title,
    //                         'slug' => $post->slug,
    //                         'excerpt' => $post->excerpt,
    //                         'published_at' => $post->published_at,
    //                         'original_url' => $media ? ($media->disk === 'r2' 
    //                             ? config('filesystems.disks.r2.url') . '/' . $media->id . '/' . $media->file_name
    //                             : asset('storage/' . $media->id . '/' . $media->file_name)) : null
    //                     ];
    //                 }),

    //             'breaking' => Post::select('id', 'title', 'slug', 'excerpt', 'published_at')
    //                 ->with(['media' => function($query) {
    //                     $query->select('id', 'model_id', 'file_name', 'disk');
    //                 }])
    //                 ->where('status', 'published')
    //                 ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_breaking' = 'true'")
    //                 ->latestPublished()
    //                 ->take(5)
    //                 ->get()
    //                 ->map(function($post) {
    //                     $media = $post->media->first();
    //                     return [
    //                         'id' => $post->id,
    //                         'title' => $post->title,
    //                         'slug' => $post->slug,
    //                         'excerpt' => $post->excerpt,
    //                         'published_at' => $post->published_at,
    //                         'original_url' => $media ? ($media->disk === 'r2' 
    //                             ? config('filesystems.disks.r2.url') . '/' . $media->id . '/' . $media->file_name
    //                             : asset('storage/' . $media->id . '/' . $media->file_name)) : null
    //                     ];
    //                 }),
                    
    //             'top_post_news' => Post::select('id', 'title', 'slug', 'excerpt', 'published_at')
    //                 ->with(['media' => function($query) {
    //                     $query->select('id', 'model_id', 'file_name', 'disk');
    //                 }])
    //                 ->where('status', 'published')
    //                 ->whereHas('categories', function ($q) {
    //                     $q->whereRaw('LOWER(name) = ?', ['top-post']);
    //                 })
    //                 ->latestPublished()
    //                 ->take(12)
    //                 ->get()
    //                 ->map(function($post) {
    //                     $media = $post->media->first();
    //                     return [
    //                         'id' => $post->id,
    //                         'title' => $post->title,
    //                         'slug' => $post->slug,
    //                         'excerpt' => $post->excerpt,
    //                         'published_at' => $post->published_at,
    //                         'original_url' => $media ? ($media->disk === 'r2' 
    //                             ? config('filesystems.disks.r2.url') . '/' . $media->id . '/' . $media->file_name
    //                             : asset('storage/' . $media->id . '/' . $media->file_name)) : null
    //                     ];
    //                 }),
    //         ];
    //     });

    //     return response()->json($data);
    // }
    
    private function formatPostData($posts)
    {
        return $posts->map(function($post) {
            $media = $post->media->first();
            $categories = $post->categories->map(function($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'name_bn' => $cat->name_bn,
                ];
            });
            
            return [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'published_at' => $post->published_at,
                'feature_image_link' => $post->feature_image_link,
                'original_url' => $media ? ($media->disk === 'r2' 
                    ? config('filesystems.disks.r2.url') . '/' . $media->id . '/' . $media->file_name
                    : asset('storage/' . $media->id . '/' . $media->file_name)) : null,
                'categories' => $categories
            ];
        });
    }

    public function home()
    {
        $data = Cache::remember('api_home', 300, function () {
            $selectFields = ['id', 'title', 'slug', 'excerpt', 'published_at', 'feature_image_link'];
            $mediaQuery = function($query) {
                $query->select('id', 'model_id', 'file_name', 'disk');
            };
            $categoriesQuery = function($query) {
                $query->select('terms.id', 'terms.name', 'terms.slug', 'terms.name_bn');
            };

            return [
                'featured' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_featured' = 'true'")
                        ->latestPublished()
                        ->take(4)
                        ->get()
                ),
                'latest' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->latestPublished()
                        ->take(12)
                        ->get()
                ),
                'breaking' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_breaking' = 'true'")
                        ->latestPublished()
                        ->take(5)
                        ->get()
                ),
                'popular' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->orderBy('views', 'desc')
                        ->take(20)
                        ->get()
                ),
                'world_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['international']);
                        })
                        ->latestPublished()
                        ->take(9)
                        ->get()
                ),
                'bangladeshi_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['bangladesh']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'politics_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['politics']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'crime_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['crime']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'mixed_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['mix']);
                        })
                        ->latestPublished()
                        ->take(20)
                        ->get()
                ),
                'economy_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['economy']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'science_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['science']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'technology_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['technology']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'sports_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['sports']);
                        })
                        ->latestPublished()
                        ->take(4)
                        ->get()
                ),
                'entertainment_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['entertainment']);
                        })
                        ->latestPublished()
                        ->take(4)
                        ->get()
                ),
                'country_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['country']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'engineering_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['engineering']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'health_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['health']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'success_storys_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['success-story']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'lifestyle_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['lifestyle']);
                        })
                        ->latestPublished()
                        ->take(4)
                        ->get()
                ),
                'multimedia_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['multimedia']);
                        })
                        ->latestPublished()
                        ->take(20)
                        ->get()
                ),
                'education_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['education']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'environment_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['environment']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'interview_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['interview']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'corporate_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['corporate-news']);
                        })
                        ->latestPublished()
                        ->take(3)
                        ->get()
                ),
                'photo_feature_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['photo-feature']);
                        })
                        ->latestPublished()
                        ->take(20)
                        ->get()
                ),
                'opinion_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['opinion']);
                        })
                        ->latestPublished()
                        ->take(4)
                        ->get()
                ),
                'literature_popular_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['literature']);
                        })
                        ->latestPublished()
                        ->take(6)
                        ->get()
                ),
                'top_post_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['top-post']);
                        })
                        ->latestPublished()
                        ->take(12)
                        ->get()
                ),
            ];
        });

        return response()->json($data);
    }

    public function homeOne()
    {
        $data = Cache::remember('api_home_one', 300, function () {
            $selectFields = ['id', 'title', 'slug', 'excerpt', 'published_at', 'feature_image_link'];
            $mediaQuery = function($query) {
                $query->select('id', 'model_id', 'file_name', 'disk');
            };
            $categoriesQuery = function($query) {
                $query->select('terms.id', 'terms.name', 'terms.slug', 'terms.name_bn');
            };

            return [
                'featured' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_featured' = 'true'")
                        ->latestPublished()
                        ->take(4)
                        ->get()
                ),
                'breaking' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_breaking' = 'true'")
                        ->latestPublished()
                        ->take(5)
                        ->get()
                ),
                'top_post_news' => $this->formatPostData(
                    Post::select($selectFields)
                        ->with(['media' => $mediaQuery, 'categories' => $categoriesQuery])
                        ->where('status', 'published')
                        ->whereHas('categories', function ($q) {
                            $q->whereRaw('LOWER(name) = ?', ['top-post']);
                        })
                        ->latestPublished()
                        ->take(12)
                        ->get()
                ),
            ];
        });

        return response()->json($data);
    }

    public function post($slug)
    {
        $post = Post::with(['user', 'reporter.user', 'categories', 'tags', 'media'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $post->increment('views');

        $related = Post::with(['user', 'reporter.user', 'media'])
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->whereHas('categories', function ($q) use ($post) {
                $q->whereIn('id', $post->categories->pluck('id'));
            })
            ->latestPublished()
            ->take(50)
            ->get();
            
        $post->setAttribute('published_at', $post->published_at ? date('Y-m-d H:i:s', strtotime($post->published_at)) : null);
    
        $related->each(function($item) {
            $item->setAttribute('published_at', $item->published_at ? date('Y-m-d H:i:s', strtotime($item->published_at)) : null);
        });

        return response()->json([
            'post' => $post,
            'related' => $related,
        ]);
    }

    public function category($slug)
    {
        $category = Term::where('slug', $slug)->firstOrFail();

        $posts = Post::with(['user', 'categories', 'media'])
            ->where('status', 'published')
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('id', $category->id);
            })
            ->latestPublished()
            ->paginate(20);

        return response()->json([
            'category' => $category,
            'posts' => $posts,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $posts = Post::with(['user', 'categories', 'media'])
            ->where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->latestPublished()
            ->paginate(20);

        return response()->json($posts);
    }

    /**
     * Get all categories with subcategories for menu
     */
    public function getMenuCategories()
    {
        $categories = Cache::remember('frontend_menu_categories', 3600, function () {
            // Get all categories with parent-child relationships
            $allCategories = Term::where('taxonomy', 'category') // Changed 'type' to 'taxonomy'
                ->select('id', 'name', 'name_bn', 'slug', 'parent_id', 'description', 'color')
                ->orderBy('parent_id') // Add ordering
                ->orderBy('id')
                ->get()
                ->toArray();

            // Build hierarchical array
            return $this->buildCategoryTree($allCategories);
        });

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Build hierarchical category tree
     */
    private function buildCategoryTree(array $categories, $parentId = 0): array
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] == $parentId) {
                $children = $this->buildCategoryTree($categories, $category['id']);
                
                $branch[] = [
                    'id' => $category['id'],
                    'name' => $category['name'],
                    'name_bn' => $category['name_bn'],
                    'slug' => $category['slug'],
                    'parent_id' => $category['parent_id'],
                    'description' => $category['description'],
                    'color' => $category['color'],
                    'children' => $children
                ];
            }
        }

        return $branch;
    }

    /**
     * Only shows other category all sub categorys with id, name, name_bn, slug, parent_id 
     */

    public function getOtherCategories()
    {
        // Only sub-categories (parent_id != 0)
        $subCategories = Term::where('taxonomy', 'category')
            ->where('parent_id', '!=', 0)
            ->with(['menuOrder'])
            ->orderByRaw('
                (SELECT menu_order FROM term_menu_orders WHERE term_id = terms.id) ASC,
                name ASC
            ')
            ->get(['id', 'name', 'name_bn', 'parent_id', 'slug']);

        // Attach term_id from TermMenuOrder if exists
        $data = $subCategories->map(function ($term) {
            $menu = $term->menuOrder;
            return [
                'id' => $term->id,
                'term_id' => $menu?->id, // nullable
                'name' => $term->name,
                'name_bn' => $term->name_bn,
                'parent_id' => $term->parent_id,
                'slug' => $term->slug,
                'menu_order' => $menu?->menu_order ?? 0,
            ];
        });

        return response()->json([
            'success' => true,
            'categories' => $data,
        ]);
    }

    /**
     * Only shows main categories (parent_id == null) with id, name, name_bn, slug, parent_id 
     */
    public function getMainCategories()
    {
        // Only top-level categories (parent_id = null or 0)
        $orders = MainMenuOrder::with('term') // join with term data
            ->orderBy('menu_order', 'asc')
            ->get();

        // Map data for API
        $data = $orders->map(function ($order) {
            $term = $order->term;
            return [
                'id' => $term->id,
                'term_id' => $term->id,
                'name' => $term->name,
                'name_bn' => $term->name_bn,
                'slug' => $term->slug,
                'menu_order' => $order->menu_order,
            ];
        });

        return response()->json([
            'success' => true,
            'categories' => $data,
        ]);
    }


    /**
     * Get reporter profile with all data and posts
     */
    public function getReporter($slug, Request $request)
    {
        // Get reporter with details
        $reporter = Cache::remember("reporter_full_{$slug}", 1800, function () use ($slug) {
            $reporter = Reporter::with(['user'])
                ->where('slug', $slug)
                ->firstOrFail();
            
            // Format reporter data based on type
            $reporterData = [
                'id' => $reporter->id,
                'type' => $reporter->type,
                'slug' => $reporter->slug,
                'verification_status' => $reporter->verification_status,
                'rating' => $reporter->rating,
                'rating_count' => $reporter->rating_count,
                'experience' => $reporter->experience,
                'specialization' => $reporter->specialization,
                'total_articles' => $reporter->total_articles,
                'social_media' => $reporter->social_media,
                'created_at' => $reporter->created_at,
            ];
            
            // Add type-specific fields
            if ($reporter->type === 'desk') {
                $reporterData['name'] = $reporter->desk_name;
                $reporterData['bio'] = $reporter->bio;
                $reporterData['location'] = $reporter->location;
                $reporterData['location_updated_at'] = $reporter->location_updated_at;
                $reporterData['photo'] = $reporter->getFirstMediaUrl('photo');
                // User details if available
                if ($reporter->user) {
                    $reporterData['user_details'] = [
                        'id' => $reporter->user->id,
                        'username' => $reporter->user->username,
                        'email' => $reporter->user->email,
                        'avatar' => $reporter->user->avatar_url,
                        'bio' => $reporter->user->bioMeta?->meta_value,
                    ];
                }
            } else { // human
                $reporterData['name'] = $reporter->user ? $reporter->user->full_name : 'N/A';
                $reporterData['designation'] = $reporter->designation;
                $reporterData['age'] = $reporter->age;
                $reporterData['bio'] = $reporter->bio;
                $reporterData['location'] = $reporter->location;
                $reporterData['location_updated_at'] = $reporter->location_updated_at;
                $reporterData['photo'] = $reporter->getFirstMediaUrl('photo');
                
                // User details if available
                if ($reporter->user) {
                    $reporterData['user_details'] = [
                        'id' => $reporter->user->id,
                        'username' => $reporter->user->username,
                        'email' => $reporter->user->email,
                        'avatar' => $reporter->user->avatar_url,
                        'bio' => $reporter->user->bioMeta?->meta_value,
                    ];
                }
            }
            
            return $reporterData;
        });
        
        // Get all posts by this reporter (desc order)
        $posts = Post::with(['categories', 'media'])
            ->where('reporter_id', $reporter['id'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(100);
        
        // Get statistics
        $stats = [
            'total_posts' => Post::where('reporter_id', $reporter['id'])
                ->where('status', 'published')
                ->count(),
            'total_views' => Post::where('reporter_id', $reporter['id'])
            ->where('status', 'published') // Added this condition
            ->sum('views'),
            'avg_views_per_post' => Post::where('reporter_id', $reporter['id'])
                ->where('status', 'published')
                ->avg('views') ?? 0,
            'featured_posts' => Post::where('reporter_id', $reporter['id'])
                ->where('status', 'published')
                // ->whereJsonContains('post_type_meta->is_featured', true)
                ->whereRaw("CAST(post_type_meta AS jsonb)->>'is_featured' = 'true'")
                ->count(),
        ];
        
        return response()->json([
            'success' => true,
            'reporter' => $reporter,
            'stats' => $stats,
            'posts' => $posts
        ]);
    }

    /**
     * Get active online voting question with results
     */
    public function getActiveVoteQuestion()
    {
        $question = Question::where('is_active', true)
            ->withCount([
                'votes as yes_count' => fn($q) => $q->where('vote_option', 'yes'),
                'votes as no_count' => fn($q) => $q->where('vote_option', 'no'),
                'votes as no_comment_count' => fn($q) => $q->where('vote_option', 'no_comment'),
                'votes as total_votes'
            ])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$question) {
            return response()->json([
                'success' => false,
                'message' => 'No active voting question found',
                'has_active_question' => false
            ]);
        }

        $userHasVoted = $this->checkUserVoted($question->id);
        $userVote = $userHasVoted ? $this->getUserVote($question->id) : null;

        $totalVotes = $question->total_votes;

        $formattedQuestion = [
            'id' => $question->id,
            'question_text' => $question->question_text,
            'question_date' => $question->question_date ? 
                $question->question_date->format('d F Y') : 
                $question->created_at->format('d F Y'),
            'is_active' => $question->is_active,
            'total_votes' => $totalVotes,
            'options' => [
                [
                    'value' => 'yes',
                    'label' => 'হাঁয়া ভোট',
                    'count' => $question->yes_count,
                    'percentage' => $totalVotes > 0 ? 
                        round(($question->yes_count / $totalVotes) * 100, 2) : 0
                ],
                [
                    'value' => 'no',
                    'label' => 'না ভোট',
                    'count' => $question->no_count,
                    'percentage' => $totalVotes > 0 ? 
                        round(($question->no_count / $totalVotes) * 100, 2) : 0
                ],
                [
                    'value' => 'no_comment',
                    'label' => 'মন্তব্য নেই',
                    'count' => $question->no_comment_count,
                    'percentage' => $totalVotes > 0 ? 
                        round(($question->no_comment_count / $totalVotes) * 100, 2) : 0
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'has_active_question' => true,
            'question' => $formattedQuestion,
            'user_has_voted' => $userHasVoted,
            'user_vote' => $userVote,
            'vote_results' => [
                'yes_percentage' => $totalVotes > 0 ? 
                    round(($question->yes_count / $totalVotes) * 100, 2) : 0,
                'no_percentage' => $totalVotes > 0 ? 
                    round(($question->no_count / $totalVotes) * 100, 2) : 0,
                'no_comment_percentage' => $totalVotes > 0 ? 
                    round(($question->no_comment_count / $totalVotes) * 100, 2) : 0,
                'total_voters' => $totalVotes
            ]
        ]);
    }

    /**
     * Submit a vote for a question
     */
    public function submitVote(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'vote_option' => 'required|in:yes,no,no_comment'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $question = Question::findOrFail($request->question_id);

        // Check if question is active
        if (!$question->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Voting is closed for this question'
            ], 403);
        }

        // Check if user has already voted
        if ($this->checkUserVoted($question->id)) {
            return response()->json([
                'success' => false,
                'message' => 'আপনি ইতিমধ্যেই ভোট দিয়েছেন'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Create vote
            $vote = Vote::create([
                'question_id' => $question->id,
                'vote_option' => $request->vote_option,
                'user_id' => auth()->check() ? auth()->id() : null,
                'ip_address' => auth()->check() ? null : $request->ip()
            ]);

            // Get updated counts
            $question->refresh();
            $question->loadCount([
                'votes as yes_count',
                'votes as no_count',
                'votes as no_comment_count',
                'votes as total_votes'
            ]);

            DB::commit();

            $totalVotes = $question->total_votes;

            return response()->json([
                'success' => true,
                'message' => 'আপনার ভোট সফলভাবে দেওয়া হয়েছে',
                'vote' => [
                    'id' => $vote->id,
                    'option' => $vote->vote_option,
                    'option_label' => $this->getOptionLabel($vote->vote_option),
                    'voted_at' => $vote->created_at->format('d F Y h:i A')
                ],
                'updated_results' => [
                    'question_id' => $question->id,
                    'total_votes' => $totalVotes,
                    'options' => [
                        'yes' => [
                            'count' => $question->yes_count,
                            'percentage' => $totalVotes > 0 ? 
                                round(($question->yes_count / $totalVotes) * 100, 2) : 0
                        ],
                        'no' => [
                            'count' => $question->no_count,
                            'percentage' => $totalVotes > 0 ? 
                                round(($question->no_count / $totalVotes) * 100, 2) : 0
                        ],
                        'no_comment' => [
                            'count' => $question->no_comment_count,
                            'percentage' => $totalVotes > 0 ? 
                                round(($question->no_comment_count / $totalVotes) * 100, 2) : 0
                        ]
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'ভোট দেওয়া সম্ভব হয়নি',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get vote results for a specific question
     */
    public function getVoteResults($questionId)
    {
        $question = Question::withCount([
                'votes as yes_count',
                'votes as no_count',
                'votes as no_comment_count',
                'votes as total_votes'
            ])
            ->findOrFail($questionId);

        $totalVotes = $question->total_votes;

        return response()->json([
            'success' => true,
            'question' => [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'is_active' => $question->is_active
            ],
            'results' => [
                'total_votes' => $totalVotes,
                'yes' => [
                    'count' => $question->yes_count,
                    'percentage' => $totalVotes > 0 ? 
                        round(($question->yes_count / $totalVotes) * 100, 2) : 0
                ],
                'no' => [
                    'count' => $question->no_count,
                    'percentage' => $totalVotes > 0 ? 
                        round(($question->no_count / $totalVotes) * 100, 2) : 0
                ],
                'no_comment' => [
                    'count' => $question->no_comment_count,
                    'percentage' => $totalVotes > 0 ? 
                        round(($question->no_comment_count / $totalVotes) * 100, 2) : 0
                ]
            ],
            'user_has_voted' => $this->checkUserVoted($question->id),
            'user_vote' => $this->getUserVote($question->id)
        ]);
    }

    /**
     * Check if current user has voted on a question
     */
    private function checkUserVoted($questionId)
    {
        $query = Vote::where('question_id', $questionId);

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('ip_address', request()->ip());
        }

        return $query->exists();
    }

    /**
     * Get user's vote for a question
     */
    private function getUserVote($questionId)
    {
        $query = Vote::where('question_id', $questionId);

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->where('ip_address', request()->ip());
        }

        $vote = $query->first();
        
        if (!$vote) {
            return null;
        }

        return [
            'option' => $vote->vote_option,
            'option_label' => $this->getOptionLabel($vote->vote_option),
            'voted_at' => $vote->created_at->format('d F Y h:i A')
        ];
    }

    /**
     * Get Bangla label for vote option
     */
    private function getOptionLabel($option)
    {
        $labels = [
            'yes' => 'হাঁয়া ভোট',
            'no' => 'না ভোট',
            'no_comment' => 'মন্তব্য নেই'
        ];

        return $labels[$option] ?? $option;
    }

    /**
     * Get general website settings
     */
    public function getGeneralSettings()
    {
        $settings = GeneralSetting::first();
        
        if (!$settings) {
            $settings = GeneralSetting::create([]);
        }
        
        // Just modify these 2 fields with full URL
        $settings->site_logo = $settings->site_logo ? 
            asset('images/website_settings/' . $settings->site_logo) : null;
        
        $settings->fav_icon = $settings->fav_icon ? 
            asset('images/website_settings/' . $settings->fav_icon) : null;
        
        return response()->json([
            'settings' => $settings,
        ]);
    }

    // Newsletter subscription methods will go here

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Check if email exists with unsubscribed status
        $existingSubscriber = Subscriber::where('email', $request->email)->first();

        if ($existingSubscriber) {
            if ($existingSubscriber->status === 'unsubscribed') {
                // Update the existing record
                $existingSubscriber->update([
                    'status' => 'subscribed',
                    'confirmation_token' => Str::random(32), // Generate new token if needed
                    // Add any other fields you want to update
                ]);
                
                return response()->json(['message' => 'Thank you for subscribing again.']);
            }
            
            // If email exists and status is not unsubscribed, return error
            return response()->json([
                'message' => 'This email is already subscribed.'
            ], 422);
        }

        // Create new subscriber if email doesn't exist
        $token = Str::random(32);

        $subscriber = Subscriber::create([
            'email' => $request->email,
            'confirmation_token' => $token,
            'status' => 'subscribed'
        ]);

        // Send confirmation email
        // Mail::send('emails.confirmation', ['token' => $token], function($message) use ($request){
        //     $message->to($request->email)
        //             ->subject('Please confirm your subscription');
        // });

        return response()->json(['message' => 'Thank you for your subscription.']);
    }

    // Confirm subscription
    public function confirm($token)
    {
        $subscriber = Subscriber::where('confirmation_token', $token)->firstOrFail();

        $subscriber->status = 'subscribed';
        $subscriber->save();

        return "Subscription confirmed! Thank you.";
    }

    // Unsubscribe
    public function unsubscribe($token)
    {
        $subscriber = Subscriber::where('confirmation_token', $token)->orWhere('email', $token)->firstOrFail();

        $subscriber->status = 'unsubscribed';
        $subscriber->confirmation_token = null;
        $subscriber->save();

        return "You have unsubscribed successfully.";
    }

    //Get Adds Data

    public function getAdvertisements(Request $request)
    {
        $ads = Advertisement::select([
                'id',
                'title',
                'content',
                'ad_type',
                'placement',
                'image',
                'link_url',
                'post_id',
                'status'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(20) // backend e same
            ->withQueryString();

        // Full image URL
        $ads->getCollection()->transform(function($ad){
            if($ad->image){
                $ad->image = asset($ad->image);
            }
            return $ad;
        });

        return response()->json($ads);
    }

    /**
     * Get post by slug with next 25 posts
     * Returns the searched post + 25 posts after it ordered by published_at
     */
    public function getPostWithNext(Request $request)
    {
        $slug = $request->input('slug');
        
        if (!$slug) {
            return response()->json([
                'success' => false,
                'message' => 'Slug parameter is required'
            ], 400);
        }

        // Find the post by slug
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }

        // Get 25 posts after this post based on published_at
        $nextPosts = Post::select(['id', 'title', 'slug', 'published_at', 'created_at'])
            ->where('status', 'published')
            ->where(function($query) use ($post) {
                $query->where('published_at', '<', $post->published_at)
                    ->orWhere(function($q) use ($post) {
                        $q->where('published_at', '=', $post->published_at)
                          ->where('id', '<', $post->id);
                    });
            })
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc')
            ->take(25)
            ->get();

        // Combine the searched post with next 25 posts
        $allPosts = collect([$post])->merge($nextPosts);

        // Format the response
        $formattedPosts = $allPosts->map(function($p) {
            return [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'published_at' => $p->published_at ? $p->published_at->format('Y-m-d H:i:s') : null,
                'created_at' => $p->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'total' => $formattedPosts->count(),
            'data' => $formattedPosts
        ]);
    }

    /**
     * Update post published_at date by slug
     */
    public function updatePublishedDate(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string',
            'published_at' => 'required|string'
        ]);

        $post = Post::where('slug', $validated['slug'])->first();

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found with slug: ' . $validated['slug']
            ], 404);
        }

        try {
            $oldDate = $post->published_at;
            $post->published_at = $validated['published_at'];
            $post->save();

            return response()->json([
                'success' => true,
                'message' => 'Published date updated successfully',
                'data' => [
                    'id' => $post->id,
                    'slug' => $post->slug,
                    'title' => $post->title,
                    'old_published_at' => $oldDate ? $oldDate->format('Y-m-d H:i:sP') : null,
                    'new_published_at' => $post->published_at->format('Y-m-d H:i:sP')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid date format. Please use format like: 2026-04-05 15:55:00+00',
                'error' => $e->getMessage()
            ], 400);
        }
    }

}