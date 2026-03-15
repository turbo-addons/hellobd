@section('title', __('Dashboard') . ' | ' . config('app.name'))

@php
    $dashboardSections = Hook::applyFilters(DashboardFilterHook::DASHBOARD_SECTIONS, [
        'quick_actions',
        'stat_cards',
        'user_growth',
        'quick_draft',
        'post_chart',
        'recent_posts',
    ]);
@endphp

<x-layouts.backend-layout>
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-white/90 flex items-center gap-2">
            {{ __('Hi :name', ['name' => auth()->user()->full_name]) }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Welcome back to the dashboard!') }}
        </p>
    </div>

    {!! Hook::applyFilters(DashboardFilterHook::DASHBOARD_AFTER_BREADCRUMBS, '') !!}

    {{-- Statistics Overview --}}
    <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-8">
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Total Posts') }}</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $total_posts }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Published') }}</p>
            <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400">{{ $published_posts }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Draft Posts') }}</p>
            <p class="mt-1 text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $draft_posts }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Total Users') }}</p>
            <p class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $total_users }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Reporters') }}</p>
            <p class="mt-1 text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $reporters }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Editors') }}</p>
            <p class="mt-1 text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $editors }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Vendors') }}</p>
            <p class="mt-1 text-2xl font-bold text-pink-600 dark:text-pink-400">{{ $vendors }}</p>
        </div>
        <div class="rounded-lg bg-white p-4 shadow dark:bg-gray-800">
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Active Ads') }}</p>
            <p class="mt-1 text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $active_ads }}</p>
        </div>
    </div>

    {{-- Quick Actions Panel --}}
    @if(in_array('quick_actions', $dashboardSections))
    <div class="mb-6">
        @include('backend.pages.dashboard.partials.quick-actions')
    </div>
    @endif

    @section('before_vite_build')
        <script>
            var userGrowthData = @json($user_growth_data['data']);
            var userGrowthLabels = @json($user_growth_data['labels']);
        </script>
    @endsection

    {{-- Charts Row: User Growth + Quick Draft --}}
    @if(in_array('user_growth', $dashboardSections) || in_array('quick_draft', $dashboardSections))
    @can('user.view')
    <div class="mt-6">
        <div class="grid grid-cols-12 gap-4 md:gap-6">
            {{-- User Growth Chart --}}
            @if(in_array('user_growth', $dashboardSections))
            <div class="col-span-12 lg:col-span-8">
                @include('backend.pages.dashboard.partials.user-growth')
            </div>
            @endif
            {{-- Quick Draft Form --}}
            @if(in_array('quick_draft', $dashboardSections))
            <div class="col-span-12 md:col-span-6 lg:col-span-4">
                @can('post.create')
                <livewire:dashboard.quick-draft />
                @endcan
            </div>
            @endif
        </div>
    </div>
    @endcan
    @endif

    {{-- Bottom Row: Post Activity + Recent Posts --}}
    @if(in_array('post_chart', $dashboardSections) || in_array('recent_posts', $dashboardSections))
    <div class="mt-6">
        <div class="grid grid-cols-12 gap-4 md:gap-6">
            {{-- Post Activity Chart --}}
            @if(in_array('post_chart', $dashboardSections))
            @can('post.view')
            <div class="col-span-12 lg:col-span-8">
                <div class="grid grid-cols-12 gap-4 md:gap-6">
                    @include('backend.pages.dashboard.partials.post-chart')
                </div>
            </div>
            @endcan
            @endif

            {{-- Recent Posts Sidebar --}}
            @if(in_array('recent_posts', $dashboardSections))
            <div class="col-span-12 lg:col-span-4">
                @can('post.view')
                <livewire:dashboard.recent-posts :limit="5" />
                @endcan
            </div>
            @endif
        </div>
    </div>
    @endif

    {!! Hook::applyFilters(DashboardFilterHook::DASHBOARD_AFTER, '') !!}
</x-layouts.backend-layout>
