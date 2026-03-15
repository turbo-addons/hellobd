<x-layouts.backend-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Billing & Revenue Report</h1>
    </div>

    <!-- Revenue Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</div>
            <div class="text-2xl font-bold text-green-600">${{ number_format($stats['total_revenue'], 2) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Recharged</div>
            <div class="text-2xl font-bold text-blue-600">${{ number_format($stats['total_recharged'], 2) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Active Ads</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_ads'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Impressions</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_impressions']) }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Clicks</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_clicks']) }}</div>
        </div>
    </div>

    <!-- Vendor Performance -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Vendor Performance</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Wallet Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Active Ads</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total Spent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Impressions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Clicks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($vendors as $vendor)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $vendor['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ $vendor['email'] }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($vendor['wallet_balance'], 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $vendor['active_ads'] }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-red-600">${{ number_format($vendor['total_spent'], 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ number_format($vendor['total_impressions']) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ number_format($vendor['total_clicks']) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.vendors.show', $vendor['id']) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Ad Spending</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Vendor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentTransactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $transaction->vendor->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $transaction->description }}</td>
                        <td class="px-6 py-4 text-sm text-right font-semibold text-red-600">${{ number_format($transaction->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No transactions yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.backend-layout>
