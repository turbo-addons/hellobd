<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                @if($reporter->photo_url)
                    <img src="{{ $reporter->photo_url }}" alt="{{ $reporter->display_name }}" class="h-20 w-20 rounded-full object-cover">
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-2xl font-bold text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                        {{ mb_substr($reporter->display_name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $reporter->display_name }}</h2>
                    @if($reporter->designation)
                        <p class="text-gray-600 dark:text-gray-400">{{ $reporter->designation }}</p>
                    @endif
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-yellow-500">★ {{ number_format($reporter->rating, 1) }}</span>
                        <span class="text-sm text-gray-500">({{ $reporter->rating_count }} {{ __('ratings') }})</span>
                        <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-medium {{ $reporter->verification_status === 'verified' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                            {{ ucfirst($reporter->verification_status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                @if($reporter->verification_status === 'pending')
                    <form method="POST" action="{{ route('admin.reporters.update', $reporter) }}" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="verification_status" value="verified">
                        <input type="hidden" name="type" value="{{ $reporter->type }}">
                        <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                            {{ __('Approve') }}
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.reporters.edit', $reporter) }}" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    {{ __('Edit') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Personal Info') }}</h3>
                <dl class="space-y-3">
                    @if($reporter->type === 'human' && $reporter->user)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $reporter->user->email }}</dd>
                        </div>
                    @endif
                    @if($reporter->age)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Age') }}</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $reporter->age }} {{ __('years') }}</dd>
                        </div>
                    @endif
                    @if($reporter->location)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Location') }}</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $reporter->location }}</dd>
                        </div>
                    @endif
                    @if($reporter->experience)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Experience') }}</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $reporter->experience }}</dd>
                        </div>
                    @endif
                    @if($reporter->specialization)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Specialization') }}</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $reporter->specialization }}</dd>
                        </div>
                    @endif
                </dl>

                @if($reporter->social_media)
                    <div class="mt-4">
                        <h4 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Social Media') }}</h4>
                        <div class="flex gap-2">
                            @if(!empty($reporter->social_media['facebook']))
                                <a href="{{ $reporter->social_media['facebook'] }}" target="_blank" class="text-blue-600 hover:text-blue-800" title="Facebook">
                                    <iconify-icon icon="lucide:facebook" class="text-xl"></iconify-icon>
                                </a>
                            @endif
                            @if(!empty($reporter->social_media['twitter']))
                                <a href="{{ $reporter->social_media['twitter'] }}" target="_blank" class="text-blue-400 hover:text-blue-600" title="Twitter">
                                    <iconify-icon icon="lucide:twitter" class="text-xl"></iconify-icon>
                                </a>
                            @endif
                            @if(!empty($reporter->social_media['linkedin']))
                                <a href="{{ $reporter->social_media['linkedin'] }}" target="_blank" class="text-blue-700 hover:text-blue-900" title="LinkedIn">
                                    <iconify-icon icon="lucide:linkedin" class="text-xl"></iconify-icon>
                                </a>
                            @endif
                            @if(!empty($reporter->social_media['instagram']))
                                <a href="{{ $reporter->social_media['instagram'] }}" target="_blank" class="text-pink-600 hover:text-pink-800" title="Instagram">
                                    <iconify-icon icon="lucide:instagram" class="text-xl"></iconify-icon>
                                </a>
                            @endif
                            @if(!empty($reporter->social_media['youtube']))
                                <a href="{{ $reporter->social_media['youtube'] }}" target="_blank" class="text-red-600 hover:text-red-800" title="YouTube">
                                    <iconify-icon icon="lucide:youtube" class="text-xl"></iconify-icon>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Statistics') }}</h3>
                <div class="space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ number_format($reporter->posts()->count()) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Articles') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ number_format($reporter->posts()->where('status', 'published')->sum('views')) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Views') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ number_format($reporter->posts()->where('status', 'published')->count()) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Published') }}</div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Account Info') }}</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Type') }}</dt>
                        <dd class="text-gray-900 dark:text-white">{{ ucfirst($reporter->type) }}</dd>
                    </div>
                    @if($reporter->type === 'human' && $reporter->user)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('User Role') }}</dt>
                            <dd class="text-gray-900 dark:text-white">{{ $reporter->user->roles->pluck('name')->join(', ') }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Joined') }}</dt>
                        <dd class="text-gray-900 dark:text-white">{{ $reporter->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                        <dd>
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $reporter->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $reporter->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        @if($reporter->bio)
        <div class="mt-6 rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
            <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Bio') }}</h3>
            <p class="text-gray-700 dark:text-gray-300">{{ $reporter->bio }}</p>
        </div>
        @endif

        <div class="mt-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                {{ __('Recent Articles') }} ({{ number_format($reporter->posts()->count()) }} {{ __('total') }})
            </h3>

            <div class="space-y-2">
                @forelse($reporter->posts()->with('categories')->latest()->paginate(10) as $post)
                    <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-600">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <a href="{{ route('admin.posts.edit', [$post->post_type, $post]) }}" class="text-lg font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                    {{ $post->title }}
                                </a>
                                <div class="mt-1 flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                                    <span>{{ $post->created_at->format('M d, Y') }}</span>
                                    <span>•</span>
                                    <span>{{ number_format($post->views ?? 0) }} {{ __('views') }}</span>
                                    @if($post->categories->count())
                                        <span>•</span>
                                        <span>{{ $post->categories->first()->name }}</span>
                                    @endif
                                    <span>•</span>
                                    <span class="rounded-full px-2 py-0.5 text-xs {{ $post->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400">{{ __('No articles yet') }}</p>
                @endforelse
            </div>

            {{-- Pagination Links --}}
            <div class="mt-4">
                {{ $reporter->posts()->latest()->paginate(10)->links() }}
            </div>
        </div>

    </div>
</x-layouts.backend-layout>
