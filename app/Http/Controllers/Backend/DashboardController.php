<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Services\Charts\PostChartService;
use App\Services\Charts\UserChartService;
use App\Services\LanguageService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function __construct(
        private readonly UserChartService $userChartService,
        private readonly LanguageService $languageService,
        private readonly PostChartService $postChartService
    ) {
    }

    public function index()
    {
        $this->authorize('viewDashboard', User::class);

        // Cache statistics for 5 minutes
        $stats = cache()->remember('dashboard_stats', 300, function () {
            return [
                'totalPosts' => Post::count(),
                'publishedPosts' => Post::where('status', 'published')->count(),
                'draftPosts' => Post::where('status', 'draft')->count(),
                'totalUsers' => User::count(),
                'reporters' => \App\Models\Reporter::count(),
                'editors' => User::role('Editor')->count(),
                'vendors' => \App\Models\Vendor::count(),
                'activeAds' => \App\Models\Advertisement::where('status', 'active')->count(),
            ];
        });

        return view(
            'backend.pages.dashboard.index',
            [
                'total_users' => number_format($stats['totalUsers']),
                'total_posts' => number_format($stats['totalPosts']),
                'published_posts' => number_format($stats['publishedPosts']),
                'draft_posts' => number_format($stats['draftPosts']),
                'reporters' => number_format($stats['reporters']),
                'editors' => number_format($stats['editors']),
                'vendors' => number_format($stats['vendors']),
                'active_ads' => number_format($stats['activeAds']),
                'total_roles' => number_format(Role::count()),
                'total_permissions' => number_format(Permission::count()),
                'languages' => [
                    'total' => number_format(count($this->languageService->getLanguages())),
                    'active' => number_format(count($this->languageService->getActiveLanguages())),
                ],
                'user_growth_data' => $this->userChartService->getUserGrowthData(
                    request()->get('chart_filter_period', 'last_6_months')
                )->getData(true),
                'user_history_data' => $this->userChartService->getUserHistoryData(),
                'post_stats' => $this->postChartService->getPostActivityData(
                    request()->get('post_chart_filter_period', 'last_6_months')
                ),
                'recent_posts' => Post::select(['id', 'title', 'slug', 'status', 'user_id', 'reporter_id', 'created_at'])
                    ->with([
                        'author:id,first_name,last_name,username',
                        'reporter:id,type,desk_name,user_id',
                        'categories:id,name'
                    ])
                    ->where('status', 'published')
                    ->latest()
                    ->take(10)
                    ->get(),
                'top_reporters' => \App\Models\Reporter::select(['id', 'type', 'desk_name', 'user_id', 'total_articles'])
                    ->with('user:id,first_name,last_name,username')
                    ->orderBy('total_articles', 'desc')
                    ->take(5)
                    ->get(),
                'breadcrumbs' => [
                    'title' => __('Dashboard'),
                    'show_home' => false,
                    'show_current' => false,
                ],
            ]
        );
    }
}
