<x-layouts.backend-layout>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Vendors</h1>
            <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">Add Vendor</a>
        </div>
    </div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ads</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Wallet Balance</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($vendors as $vendor)
            <tr>
                <td class="px-6 py-4 text-sm">
                    <a href="{{ route('admin.vendors.show', $vendor) }}" class="font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">{{ $vendor->name }}</a>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $vendor->email }}</td>
                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $vendor->ads_count }}</td>
                <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400">${{ number_format($vendor->wallet_balance, 2) }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 rounded {{ $vendor->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                        {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-right space-x-2">
                    <a href="{{ route('admin.wallet.recharge', $vendor) }}" class="text-green-600 hover:text-green-900 dark:text-green-400">Recharge</a>
                    <a href="{{ route('admin.vendors.show', $vendor) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">View</a>
                    <a href="{{ route('admin.vendors.edit', $vendor) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Edit</a>
                    <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No vendors found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
</x-layouts.backend-layout>
