<x-layouts.backend-layout>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Advertisement Management</h1>
            <a href="{{ route('admin.ads.create') }}" class="btn btn-primary">Create New Ad</a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Ads</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Active Ads</div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</div>
        </div>
        <!-- <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Pending Approval</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Expired</div>
            <div class="text-2xl font-bold text-red-600">{{ $stats['expired'] }}</div>
        </div> -->
    </div>

    <!-- Filters -->
    <!-- <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex gap-4">
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="active">Active</option>
                <option value="paused">Paused</option>
                <option value="expired">Expired</option>
                <option value="rejected">Rejected</option>
            </select>
            <select name="billing_model" class="form-control">
                <option value="">All Billing Models</option>
                <option value="cpc">CPC</option>
                <option value="cpm">CPM</option>
                <option value="fixed">Fixed</option>
            </select>
            <select name="placement" class="form-control">
                <option value="">All Placements</option>
                <option value="header">Header</option>
                <option value="sidebar">Sidebar</option>
                <option value="footer">Footer</option>
                <option value="content">Content</option>
                <option value="homepage">Homepage</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div> -->

    <div class="mb-4">
        <input 
            type="text" 
            id="search" 
            placeholder="Search Ads..." 
            value="{{ request('search') }}"
            class="w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded bg-white text-gray-900 focus:outline-none"
        >
    </div>

    <!-- Ads List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ad Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Banner</th>
                    <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Vendor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type & Billing</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Performance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Budget & Spent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Duration</th> -->
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($ads as $ad)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $ad->title }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst($ad->placement) }}</div>
                            </div>
                        </div>
                    </td>

                    <td>
                        @if($ad->image)
                            <img src="{{ asset($ad->image) }}" class="h-10 w-50 rounded mr-3">
                        @else
                            <span class="text-xs text-gray-500">No Image</span>
                        @endif
                    </td>
                    <!-- <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $ad->vendor->name }}</td> -->
                    <!-- <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 dark:text-white">{{ ucfirst($ad->ad_type) }}</div>
                        <div class="text-xs text-gray-500">{{ strtoupper($ad->billing_model) }} - ${{ $ad->rate }}</div>
                    </td> -->
                    <!-- <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 dark:text-white">{{ number_format($ad->impressions) }} views</div>
                        <div class="text-xs text-gray-500">{{ $ad->clicks }} clicks ({{ number_format($ad->ctr, 2) }}% CTR)</div>
                    </td> -->
                    <!-- <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 dark:text-white">${{ number_format($ad->spent, 2) }}</div>
                        @if($ad->total_budget)
                        <div class="text-xs text-gray-500">of ${{ number_format($ad->total_budget, 2) }}</div>
                        @endif
                    </td> -->
                    <!-- <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($ad->status === 'active') bg-green-100 text-green-800
                            @elseif($ad->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($ad->status === 'expired') bg-red-100 text-red-800
                            @elseif($ad->status === 'paused') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($ad->status) }}
                        </span>
                    </td> -->
                    <!-- <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                        {{ $ad->start_date->format('M d') }} - {{ $ad->end_date->format('M d, Y') }}
                    </td> -->
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.ads.edit', $ad) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this ad?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No advertisements found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $ads->links() }}
    </div>
    <script>
        const searchInput = document.getElementById('search');
        let debounceTimeout;

        searchInput.addEventListener('keyup', function() {
            clearTimeout(debounceTimeout);

            debounceTimeout = setTimeout(() => {
                const query = searchInput.value;

                // Make AJAX request
                fetch("{{ route('admin.ads.index') }}?search=" + encodeURIComponent(query), {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Replace the table HTML
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, "text/html");

                    // Grab only the table div from returned HTML
                    const newTable = doc.querySelector('.bg-white.rounded-lg.shadow.overflow-hidden');
                    document.querySelector('.bg-white.rounded-lg.shadow.overflow-hidden').innerHTML = newTable.innerHTML;

                    // Update pagination
                    const newPagination = doc.querySelector('.mt-4');
                    document.querySelector('.mt-4').innerHTML = newPagination.innerHTML;
                });
            }, 300); // 300ms debounce
        });
    </script>

</x-layouts.backend-layout>
