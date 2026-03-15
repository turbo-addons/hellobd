<x-layouts.backend-layout>
    <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $vendor->name }}</h2>
                <p class="text-gray-600 dark:text-gray-400">{{ $vendor->email }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.wallet.recharge', $vendor) }}" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                    {{ __('Recharge Wallet') }}
                </a>
                <a href="{{ route('admin.vendors.edit', $vendor) }}" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    {{ __('Edit') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Wallet') }}</h3>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">${{ number_format($vendor->wallet_balance, 2) }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ __('Available Balance') }}</div>
                </div>
            </div>

            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Statistics') }}</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Active Ads') }}</dt>
                        <dd class="text-xl font-bold text-gray-900 dark:text-white">{{ $vendor->ads->where('is_active', true)->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total Spent') }}</dt>
                        <dd class="text-xl font-bold text-red-600">${{ number_format($vendor->total_spent, 2) }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Account Info') }}</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                        <dd>
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $vendor->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                {{ $vendor->is_active ? __('Active') : __('Inactive') }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Joined') }}</dt>
                        <dd class="text-sm text-gray-900 dark:text-white">{{ $vendor->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        @if($vendor->description)
        <div class="mt-6 rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
            <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Description') }}</h3>
            <p class="text-gray-700 dark:text-gray-300">{{ $vendor->description }}</p>
        </div>
        @endif

        <div class="mt-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">{{ __('Recent Transactions') }}</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">{{ __('Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">{{ __('Type') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">{{ __('Description') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500 dark:text-gray-300">{{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                        @forelse($vendor->transactions as $transaction)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $transaction->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $transaction->type === 'credit' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                    @if($transaction->status === 'pending')
                                    <span class="ml-2 rounded-full px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                        Pending
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ $transaction->description }}
                                    @if($transaction->payment_method === 'manual' && $transaction->meta)
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if(isset($transaction->meta['transaction_number']))
                                            <div>Ref: {{ $transaction->meta['transaction_number'] }}</div>
                                            @endif
                                            @if(isset($transaction->meta['deposit_proof']))
                                            <a href="{{ asset('storage/' . $transaction->meta['deposit_proof']) }}" target="_blank" class="text-blue-600 hover:underline">View Proof</a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold {{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    {{ __('No transactions yet') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.backend-layout>
