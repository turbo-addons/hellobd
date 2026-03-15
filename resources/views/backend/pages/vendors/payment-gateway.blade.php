<x-layouts.backend-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Processing Payment</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="text-center">
            <div class="mb-4">
                <svg class="animate-spin h-12 w-12 mx-auto text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Redirecting to Payment Gateway...</h2>
            <p class="text-gray-600 dark:text-gray-400">Please wait while we redirect you to SSLCommerz</p>
            <p class="text-sm text-gray-500 mt-4">Amount: ${{ number_format($transaction->amount, 2) }}</p>
        </div>

        <!-- Auto-submit form to SSLCommerz -->
        <form id="sslcommerz-form" action="{{ $apiUrl }}" method="POST" style="display: none;">
            @foreach($postData as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
        </form>
    </div>

    <script>
        // Auto-submit form after 2 seconds
        setTimeout(function() {
            document.getElementById('sslcommerz-form').submit();
        }, 2000);
    </script>
</x-layouts.backend-layout>
