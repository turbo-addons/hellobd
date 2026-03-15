<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <form method="POST" action="{{ route('admin.questions.store') }}">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Question Text') }} <span class="text-red-500">*</span></label>
                    <textarea name="question_text" rows="6" class="form-control mt-1 w-full rounded border p-2" placeholder="{{ __('Enter your question here...') }}" required>{{ old('question_text') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Enter the question that users will vote on.') }}</p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Question Date') }}</label>
                        <input type="date" name="question_date" value="{{ old('question_date') }}" class="form-control mt-1 w-full rounded border p-2">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Optional: Date associated with the question') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Active (Allow voting)') }}</span>
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Inactive questions will not accept new votes') }}</p>
                    </div>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <h4 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Vote Options') }}</h4>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-3 w-3 rounded-full bg-green-500"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">হ্যাঁ ভোট (Yes Vote)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-3 w-3 rounded-full bg-red-500"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">না ভোট (No Vote)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-3 w-3 rounded-full bg-orange-500"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">মন্তব্য নেই (No Comment)</span>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ __('These options are fixed and will be available for voting') }}</p>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        <iconify-icon icon="lucide:save" class="mr-2 h-4 w-4"></iconify-icon>
                        {{ __('Save Question') }}
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