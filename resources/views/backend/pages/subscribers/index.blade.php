<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
<div x-data="{ open: false, deleteUrl: '' }">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Subscribers') }}</h2>
    </div>

    <!-- Filter + Search Row -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <input 
            type="text" 
            id="search-input"
            placeholder="{{ __('Search by email...') }}" 
            class="w-1/2 rounded-lg border-gray-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
            value="{{ request('search') }}"
        >

        <select 
            id="status-select"
            class="w-1/2 rounded-lg border-gray-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
        >
            <option value="">{{ __('All Status') }}</option>
            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
            <option value="subscribed" {{ request('status')=='subscribed' ? 'selected' : '' }}>{{ __('Subscribed') }}</option>
            <option value="unsubscribed" {{ request('status')=='unsubscribed' ? 'selected' : '' }}>{{ __('Unsubscribed') }}</option>
        </select>
    </div>

    <!-- Subscribers Table -->
    <div class="overflow-x-auto rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="subscribers-table">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Email') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Subscribed At') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                @forelse($subscribers as $subscriber)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $subscriber->email }}</td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold leading-5
                                @if($subscriber->status=='subscribed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($subscriber->status=='pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif">
                                {{ ucfirst($subscriber->status) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $subscriber->created_at->format('M d, Y') }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                            <!-- Delete Button -->
                            <button 
                                @click="open = true; deleteUrl='{{ route('admin.subscribers.delete', $subscriber) }}'" 
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                            >
                            <!-- Lucide Trash icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2">
                                <path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>
                            </svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">{{ __('No subscribers found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            {{ $subscribers->links() }}
        </div>

    </div>

    <!-- Delete Confirmation Modal -->
    <template x-if="open">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div x-show="open" x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity" x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100" class="flex max-w-md flex-col gap-4 overflow-hidden rounded-md border border-gray-100 dark:border-gray-800 bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-300">
                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-2 dark:border-gray-800">
                    <div class="flex items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 p-1">
                        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold tracking-wide text-gray-700 dark:text-white">{{ __('Delete Subscriber') }}</h3>
                    <button @click="open = false" aria-label="close modal" class="text-gray-400 hover:bg-gray-200 hover:text-gray-700 rounded-md p-1 dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="1.4" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="px-4 text-center">
                    <p class="text-gray-500 dark:text-gray-300">{{ __('Are you sure you want to delete this Subscriber?') }}</p>
                </div>
                <div class="flex items-center justify-end gap-3 border-t border-gray-100 p-4 dark:border-gray-800">
                    <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                        {{ __('No, cancel') }}
                    </button>
                    <form :action="deleteUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-300 dark:focus:ring-red-800">
                            {{ __('Yes, Confirm') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const statusSelect = document.getElementById('status-select');

    // Function to fetch subscribers
    function fetchSubscribers() {
        const searchQuery = searchInput.value.trim();
        const status = statusSelect.value;
        const url = new URL("{{ route('admin.subscribers.index') }}", window.location.origin);

        // Add query params only if values exist
        if (searchQuery) url.searchParams.set('search', searchQuery);
        else url.searchParams.delete('search');

        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');

        // Update browser URL without reload
        window.history.replaceState({}, '', url);

        // Fetch and update table
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableBody = doc.querySelector('#subscribers-table tbody');
                const currentTableBody = document.querySelector('#subscribers-table tbody');
                if (newTableBody && currentTableBody) {
                    currentTableBody.innerHTML = newTableBody.innerHTML;
                }
            });
    }

    // Debounce for search input
    let timeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(fetchSubscribers, 400);
    });

    // Status filter change
    statusSelect.addEventListener('change', fetchSubscribers);
});
</script>
@endpush

</x-layouts.backend-layout>
