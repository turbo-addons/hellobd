<x-layouts.backend-layout :breadcrumbs="$breadcrumbs">
    <!-- Statistics Cards -->
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Reporters') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Verified Reporter') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['verified'] }}</p>
                </div>
                <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                    <svg class="h-8 w-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Articles') }}</p>
                    @php
                        $totalArticles = \App\Models\Post::count();
                    @endphp
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalArticles) }}</p>
                </div>
                <div class="rounded-full bg-yellow-100 p-3 dark:bg-yellow-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 7a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Published Articles') }}</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format(\App\Models\Post::where('status', 'published')->count()) }}</p>
                </div>
                <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
            </div>
        </div> 
    </div>

    <!-- Main Content -->
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('All Reporters') }}</h2>
            <a href="{{ route('admin.reporters.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                {{ __('Add Reporter') }}
            </a>
        </div>

        <!-- Filters -->
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <select id="status-select" class="form-select w-full rounded-lg border-gray-300 bg-white text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="verified">{{ __('Verified') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="rejected">{{ __('Rejected') }}</option>
                </select>
            </div>
            <!-- <div>
                <select id="specialization-select" class="form-select w-full rounded-lg border-gray-300 bg-white text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">{{ __('All Specializations') }}</option>
                    <option value="politics">{{ __('Politics') }}</option>
                    <option value="sports">{{ __('Sports') }}</option>
                    <option value="technology">{{ __('Technology') }}</option>
                    <option value="business">{{ __('Business') }}</option>
                    <option value="general">{{ __('General') }}</option>
                </select>
            </div> -->
            <div class="sm:col-span-2">
                <input id="search-input" type="text" placeholder="{{ __('Search reporters...') }}" class="form-input w-full rounded-lg border-gray-300 bg-white text-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="reporters-table">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Reporter') }}</th>
                        <!-- <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Rating') }}</th> -->
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Experience') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Specialization') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Posts') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Joined') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($reporters as $reporter)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                                            @php
                                                $displayName = $reporter->type === 'desk' ? $reporter->desk_name : ($reporter->user ? ($reporter->user->full_name ?? $reporter->user->first_name) : 'N/A');
                                            @endphp
                                            {{ mb_substr($displayName, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('admin.reporters.show', $reporter) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ $displayName }}
                                        </a>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $reporter->type === 'desk' ? ($reporter->user->email ?? 'N/A') : $reporter->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <!-- <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($reporter->rating))
                                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4 fill-current text-gray-300" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ number_format($reporter->rating, 1) }}</span>
                                    <span class="ml-1 text-xs text-gray-500 dark:text-gray-500">({{ $reporter->rating_count }})</span>
                                </div>
                            </td> -->
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $reporter->experience ?? __('Not specified') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $reporter->specialization ?? __('General') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold leading-5 
                                    @if($reporter->verification_status === 'verified') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($reporter->verification_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif">
                                    {{ ucfirst($reporter->verification_status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ number_format($reporter->posts()->count()) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $reporter->created_at->format('M d, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.reporters.show', $reporter) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="{{ __('View') }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.reporters.edit', $reporter) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="{{ __('Edit') }}">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No reporters found') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reporters->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const statusSelect = document.getElementById('status-select');
            const reportersTable = document.getElementById('reporters-table');
            const paginationContainer = document.querySelector('.mt-6'); // Pagination container

            function fetchReporters() {
                const params = new URLSearchParams();
                if (searchInput.value) params.set('search', searchInput.value);
                if (statusSelect.value) params.set('status', statusSelect.value);

                // Show loading indicator
                const tbody = reportersTable.querySelector('tbody');
                const originalContent = tbody.innerHTML;
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex justify-center">
                                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Loading...') }}</p>
                        </td>
                    </tr>
                `;

                // Update URL without reloading page
                const url = `{{ route('admin.reporters.index') }}?${params.toString()}`;
                window.history.replaceState({}, '', url);

                // Add CSRF token for Laravel
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    // Parse the HTML response
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract the table body
                    const newTbody = doc.querySelector('#reporters-table tbody');
                    const newPagination = doc.querySelector('.mt-6');
                    
                    if (newTbody) {
                        tbody.innerHTML = newTbody.innerHTML;
                    } else {
                        // If no tbody found, show error
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-sm text-red-500">
                                    {{ __('Error loading data. Please refresh the page.') }}
                                </td>
                            </tr>
                        `;
                    }
                    
                    // Update pagination if exists
                    if (newPagination && paginationContainer) {
                        paginationContainer.innerHTML = newPagination.innerHTML;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-4 text-sm text-red-500 dark:text-red-400">{{ __('Error loading data. Please try again.') }}</p>
                            </td>
                        </tr>
                    `;
                });
            }

            // Debounce function to prevent too many API calls
            let timeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(fetchReporters, 500);
            });
            
            statusSelect.addEventListener('change', fetchReporters);

            // Also trigger search on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(timeout);
                    fetchReporters();
                }
            });

            // Store original content for reset if needed
            let originalTableContent = reportersTable.innerHTML;
            let originalPaginationContent = paginationContainer ? paginationContainer.innerHTML : '';

            // Optional: Add reset button functionality
            const resetButton = document.createElement('button');
            resetButton.type = 'button';
            resetButton.className = 'ml-2 inline-flex items-center rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800';
            resetButton.innerHTML = `
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ __('Reset') }}
            `;
            
            resetButton.addEventListener('click', function() {
                searchInput.value = '';
                statusSelect.value = '';
                const url = `{{ route('admin.reporters.index') }}`;
                window.history.replaceState({}, '', url);
                location.reload(); // Reload to get original data
            });

            // Add reset button after the search input container
            const searchContainer = document.querySelector('.sm\\:col-span-2');
            if (searchContainer) {
                searchContainer.classList.remove('sm:col-span-2');
                searchContainer.classList.add('sm:col-span-1');
                
                const resetContainer = document.createElement('div');
                resetContainer.className = 'sm:col-span-1';
                resetContainer.appendChild(resetButton);
                
                searchContainer.parentNode.insertBefore(resetContainer, searchContainer.nextSibling);
            }
        });
    </script>

</x-layouts.backend-layout>
