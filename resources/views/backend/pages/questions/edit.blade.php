<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <form method="POST" action="{{ route('admin.questions.update', $question) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Question Text') }} <span class="text-red-500">*</span></label>
                    <textarea name="question_text" rows="6" class="form-control mt-1 w-full rounded border p-2" required>{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Question Date') }}</label>
                        <input type="date" name="question_date" value="{{ old('question_date', $question->question_date ? $question->question_date->format('Y-m-d') : '') }}" class="form-control mt-1 w-full rounded border p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ $question->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active (Allow voting)') }}</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <h4 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Vote Statistics') }}</h4>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $question->yes_count }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">হ্যাঁ ভোট</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ $question->no_count }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">না ভোট</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-500">{{ $question->no_comment_count }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">মন্তব্য নেই</div>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <div class="text-lg font-bold text-blue-600">{{ $question->total_votes }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Votes') }}</div>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        <iconify-icon icon="lucide:save" class="mr-2 h-4 w-4"></iconify-icon>
                        {{ __('Update Question') }}
                    </button>
                    <a href="{{ route('admin.questions.index') }}" class="rounded bg-gray-300 px-4 py-2 hover:bg-gray-400">
                        <iconify-icon icon="lucide:x" class="mr-2 h-4 w-4"></iconify-icon>
                        {{ __('Cancel') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</x-layouts.backend-layout>