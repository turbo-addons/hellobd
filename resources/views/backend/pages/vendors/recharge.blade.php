<x-layouts.backend-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Recharge Wallet</h1>
        <p class="text-gray-600 dark:text-gray-400">{{ $vendor->name }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="text-sm text-blue-800 dark:text-blue-200">Current Balance</div>
                    <div class="text-3xl font-bold text-blue-900 dark:text-blue-100">${{ number_format($vendor->wallet_balance, 2) }}</div>
                </div>

                <form action="{{ route('admin.wallet.initiate-payment', $vendor) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recharge Amount ($) *</label>
                            <input type="number" name="amount" step="0.01" min="10" max="100000" class="form-control" placeholder="Enter amount" required>
                            <p class="text-xs text-gray-500 mt-1">Minimum: $10, Maximum: $100,000</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method *</label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="radio" name="payment_method" value="sslcommerz" class="mr-3" checked>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">SSLCommerz</div>
                                        <div class="text-sm text-gray-500">Credit/Debit Card, bKash, Nagad, Rocket</div>
                                    </div>
                                </label>
                                <label class="flex items-center p-4 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="radio" name="payment_method" value="manual" class="mr-3" id="manual_payment">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">Cash Deposit</div>
                                        <div class="text-sm text-gray-500">Bank transfer / Cash deposit (Admin approval required)</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Manual Payment Fields (shown when Cash Deposit is selected) -->
                        <div id="manual_payment_fields" style="display: none;">
                            <div class="space-y-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transaction/Deposit Number *</label>
                                    <input type="text" name="transaction_number" class="form-control" placeholder="Enter transaction or deposit slip number">
                                    <p class="text-xs text-gray-500 mt-1">Required for verification</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Deposit Proof *</label>
                                    <input type="file" name="deposit_proof" accept="image/*,.pdf" class="form-control">
                                    <p class="text-xs text-gray-500 mt-1">Upload deposit slip, invoice, or payment receipt (Image or PDF)</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes (Optional)</label>
                                    <textarea name="payment_notes" rows="2" class="form-control" placeholder="Any additional information about the deposit"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2 pt-4">
                            <button type="submit" class="btn btn-primary">Proceed to Payment</button>
                            <a href="{{ route('admin.vendors.show', $vendor) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Methods</h3>
                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Visa / Mastercard</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>bKash</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Nagad</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Rocket</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>Bank Transfer</span>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <h4 class="font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Note</h4>
                    <p class="text-xs text-yellow-700 dark:text-yellow-300">
                        Funds will be added to your wallet immediately after successful payment. 
                        Manual payments require admin approval (1-2 business days).
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const manualRadio = document.getElementById('manual_payment');
        const manualFields = document.getElementById('manual_payment_fields');
        const allRadios = document.querySelectorAll('input[name="payment_method"]');
        
        allRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (manualRadio.checked) {
                    manualFields.style.display = 'block';
                } else {
                    manualFields.style.display = 'none';
                }
            });
        });
    });
    </script>
</x-layouts.backend-layout>
