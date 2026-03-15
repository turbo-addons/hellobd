<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Question Details') }}</h2>
                <div class="mt-2 flex items-center gap-2">
                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $question->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300' }}">
                        {{ $question->is_active ? __('Active') : __('Inactive') }}
                    </span>
                    @if($question->question_date)
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            <iconify-icon icon="lucide:calendar" class="mr-1 h-3 w-3"></iconify-icon>
                            {{ $question->question_date->format('M d, Y') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                <form action="{{ route('admin.questions.toggle-status', $question) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="rounded {{ $question->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} px-4 py-2 text-white">
                        <iconify-icon icon="lucide:{{ $question->is_active ? 'pause-circle' : 'play-circle' }}" class="mr-2 h-4 w-4"></iconify-icon>
                        {{ $question->is_active ? __('Pause Voting') : __('Activate Voting') }}
                    </button>
                </form>
                <a href="{{ route('admin.questions.edit', $question) }}" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    <iconify-icon icon="lucide:edit" class="mr-2 h-4 w-4"></iconify-icon>
                    {{ __('Edit') }}
                </a>
            </div>
        </div>

        <!-- Question Text -->
        <div class="mb-6 rounded-lg bg-gray-50 p-6 dark:bg-gray-700">
            <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Question') }}</h3>
            <p class="text-gray-700 dark:text-gray-300">{{ $question->question_text }}</p>
        </div>

        <!-- Vote Statistics -->
        <div class="mb-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Vote Statistics') }}</h3>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                <!-- Yes Votes -->
                <div class="rounded-lg border border-green-200 bg-green-50 p-6 dark:border-green-800 dark:bg-green-900/20">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $question->yes_count }}</div>
                        <div class="mt-2 text-sm font-medium text-green-700 dark:text-green-300">হ্যাঁ ভোট</div>
                        <div class="mt-1 text-sm text-green-600 dark:text-green-400">
                            @if($question->total_votes > 0)
                                {{ number_format(($question->yes_count / $question->total_votes) * 100, 2) }}%
                            @else
                                0%
                            @endif
                        </div>
                    </div>
                </div>

                <!-- No Votes -->
                <div class="rounded-lg border border-red-200 bg-red-50 p-6 dark:border-red-800 dark:bg-red-900/20">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-red-600 dark:text-red-400">{{ $question->no_count }}</div>
                        <div class="mt-2 text-sm font-medium text-red-700 dark:text-red-300">না ভোট</div>
                        <div class="mt-1 text-sm text-red-600 dark:text-red-400">
                            @if($question->total_votes > 0)
                                {{ number_format(($question->no_count / $question->total_votes) * 100, 2) }}%
                            @else
                                0%
                            @endif
                        </div>
                    </div>
                </div>

                <!-- No Comment Votes -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-700">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-gray-600 dark:text-gray-400">{{ $question->no_comment_count }}</div>
                        <div class="mt-2 text-sm font-medium text-gray-700 dark:text-gray-300">মন্তব্য নেই</div>
                        <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            @if($question->total_votes > 0)
                                {{ number_format(($question->no_comment_count / $question->total_votes) * 100, 2) }}%
                            @else
                                0%
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Total Votes -->
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-6 dark:border-blue-800 dark:bg-blue-900/20">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $question->total_votes }}</div>
                        <div class="mt-2 text-sm font-medium text-blue-700 dark:text-blue-300">{{ __('Total Votes') }}</div>
                        <div class="mt-1 text-sm text-blue-600 dark:text-blue-400">100%</div>
                    </div>
                </div>
            </div>

            <!-- Progress Bars -->
            <div class="mt-6">
                <div class="mb-2 flex justify-between text-sm">
                    <span class="font-medium text-green-600 dark:text-green-400">হাঁয়া ভোট</span>
                    <span class="text-gray-600 dark:text-gray-400">
                        @if($question->total_votes > 0)
                            {{ number_format(($question->yes_count / $question->total_votes) * 100, 2) }}%
                        @else
                            0%
                        @endif
                    </span>
                </div>
                <div class="mb-4 h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                    <div class="h-2 rounded-full bg-green-500" style="width: {{ $question->total_votes > 0 ? ($question->yes_count / $question->total_votes) * 100 : 0 }}%"></div>
                </div>

                <div class="mb-2 flex justify-between text-sm">
                    <span class="font-medium text-red-600 dark:text-red-400">না ভোট</span>
                    <span class="text-gray-600 dark:text-gray-400">
                        @if($question->total_votes > 0)
                            {{ number_format(($question->no_count / $question->total_votes) * 100, 2) }}%
                        @else
                            0%
                        @endif
                    </span>
                </div>
                <div class="mb-4 h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                    <div class="h-2 rounded-full bg-red-500" style="width: {{ $question->total_votes > 0 ? ($question->no_count / $question->total_votes) * 100 : 0 }}%"></div>
                </div>

                <div class="mb-2 flex justify-between text-sm">
                    <span class="font-medium text-gray-600 dark:text-gray-400">মন্তব্য নেই</span>
                    <span class="text-gray-600 dark:text-gray-400">
                        @if($question->total_votes > 0)
                            {{ number_format(($question->no_comment_count / $question->total_votes) * 100, 2) }}%
                        @else
                            0%
                        @endif
                    </span>
                </div>
                <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                    <div class="h-2 rounded-full bg-gray-500" style="width: {{ $question->total_votes > 0 ? ($question->no_comment_count / $question->total_votes) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Recent Votes -->
        <div>
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Recent Votes') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Voter') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Vote') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('IP Address') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($recentVotes as $vote)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($vote->user)
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $vote->user->first_name }} {{ $vote->user->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $vote->user->email }}
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Guest') }}</div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($vote->vote_option === 'yes')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">
                                            <iconify-icon icon="lucide:thumbs-up" class="h-3 w-3"></iconify-icon>
                                            হাঁয়া ভোট
                                        </span>
                                    @elseif($vote->vote_option === 'no')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">
                                            <iconify-icon icon="lucide:thumbs-down" class="h-3 w-3"></iconify-icon>
                                            না ভোট
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800 dark:bg-gray-900 dark:text-gray-300">
                                            <iconify-icon icon="lucide:message-square" class="h-3 w-3"></iconify-icon>
                                            মন্তব্য নেই
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $vote->ip_address ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $vote->created_at->format('M d, Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No votes yet') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.backend-layout>