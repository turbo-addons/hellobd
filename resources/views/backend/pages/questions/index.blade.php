<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    <!-- Statistics Cards -->
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Questions') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Active Questions') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['active'] }}</p>
                </div>
                <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586zM9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Votes') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_votes']) }}</p>
                </div>
                <div class="rounded-full bg-yellow-100 p-3 dark:bg-yellow-900">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Voters') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_voters']) }}</p>
                </div>
                <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900">
                    <svg class="h-8 w-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('All Questions') }}</h2>
            <a href="{{ route('admin.questions.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <iconify-icon icon="lucide:plus" class="mr-2 h-4 w-4"></iconify-icon>
                {{ __('Add Question') }}
            </a>
        </div>

        <!-- Filters -->
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <select class="form-select w-full rounded-lg border-gray-300 bg-white text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="inactive">{{ __('Inactive') }}</option>
                </select>
            </div>
            <div>
                <input type="date" class="form-input w-full rounded-lg border-gray-300 bg-white text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>
            <div class="sm:col-span-2">
                <input type="text" placeholder="{{ __('Search questions...') }}" class="form-input w-full rounded-lg border-gray-300 bg-white text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Question') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Votes') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Created') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($questions as $question)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                <div class="max-w-md">
                                    <a href="{{ route('admin.questions.show', $question) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ \Str::limit($question->question_text, 100) }}
                                    </a>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $question->question_date ? $question->question_date->format('M d, Y') : '—' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-2 w-2 rounded-full bg-green-500"></span>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">হ্যাঁ:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $question->yes_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-2 w-2 rounded-full bg-red-500"></span>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">না:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $question->no_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-2 w-2 rounded-full bg-orange-500"></span>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">মন্তব্য নেই:</span>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $question->no_comment_count }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <form action="{{ route('admin.questions.toggle-status', $question) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-medium 
                                        @if($question->is_active) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300 @endif">
                                        <iconify-icon icon="lucide:{{ $question->is_active ? 'check-circle' : 'x-circle' }}" class="h-3 w-3"></iconify-icon>
                                        {{ $question->is_active ? __('Active') : __('Inactive') }}
                                    </button>
                                </form>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $question->created_at->format('M d, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.questions.show', $question) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="{{ __('View') }}">
                                        <iconify-icon icon="lucide:eye" class="h-5 w-5"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.questions.edit', $question) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="{{ __('Edit') }}">
                                        <iconify-icon icon="lucide:edit" class="h-5 w-5"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.questions.delete', $question) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this question?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="{{ __('Delete') }}">
                                            <iconify-icon icon="lucide:trash-2" class="h-5 w-5"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <iconify-icon icon="lucide:help-circle" class="mx-auto h-12 w-12 text-gray-400"></iconify-icon>
                                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No questions found') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $questions->links() }}
        </div>
    </div>
</x-layouts.backend-layout>