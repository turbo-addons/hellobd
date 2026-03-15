<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function recharge(Vendor $vendor)
    {
        return view('backend.pages.vendors.recharge', compact('vendor'));
    }

    public function initiatePayment(Request $request, Vendor $vendor)
    {
        $rules = [
            'amount' => 'required|numeric|min:10|max:100000',
            'payment_method' => 'required|in:sslcommerz,manual',
        ];

        // Add validation rules only for manual payment
        if ($request->payment_method === 'manual') {
            $rules['transaction_number'] = 'required|string';
            $rules['deposit_proof'] = 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120';
            $rules['payment_notes'] = 'nullable|string';
        }

        $data = $request->validate($rules);

        $transactionId = 'TXN-' . strtoupper(Str::random(12));
        $meta = [];

        // Handle manual payment proof upload
        if ($data['payment_method'] === 'manual' && $request->hasFile('deposit_proof')) {
            $proofPath = $request->file('deposit_proof')->store('deposit-proofs', 'public');
            $meta['deposit_proof'] = $proofPath;
            $meta['transaction_number'] = $data['transaction_number'] ?? null;
            $meta['payment_notes'] = $data['payment_notes'] ?? null;
        }

        // Create pending transaction
        $transaction = WalletTransaction::create([
            'vendor_id' => $vendor->id,
            'type' => 'credit',
            'amount' => $data['amount'],
            'balance_after' => $vendor->wallet_balance,
            'description' => 'Wallet recharge',
            'payment_method' => $data['payment_method'],
            'transaction_id' => $transactionId,
            'status' => 'pending',
            'meta' => $meta,
        ]);

        if ($data['payment_method'] === 'sslcommerz') {
            return $this->initSSLCommerz($transaction);
        }

        // Manual payment - admin will approve
        return redirect()->route('admin.vendors.show', $vendor)
            ->with('success', 'Cash deposit request submitted with proof. Admin will verify and approve shortly.');
    }

    private function initSSLCommerz(WalletTransaction $transaction)
    {
        // SSLCommerz configuration (add to .env)
        $storeId = config('services.sslcommerz.store_id');
        $storePassword = config('services.sslcommerz.store_password');
        $isSandbox = config('services.sslcommerz.sandbox', true);

        $postData = [
            'store_id' => $storeId,
            'store_passwd' => $storePassword,
            'total_amount' => $transaction->amount,
            'currency' => 'BDT',
            'tran_id' => $transaction->transaction_id,
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.fail'),
            'cancel_url' => route('payment.cancel'),
            'product_name' => 'Wallet Recharge',
            'product_category' => 'Service',
            'product_profile' => 'general',
            'cus_name' => $transaction->vendor->name,
            'cus_email' => $transaction->vendor->email ?? 'vendor@example.com',
            'cus_phone' => $transaction->vendor->phone ?? '01700000000',
            'cus_add1' => $transaction->vendor->address ?? 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_country' => 'Bangladesh',
            'shipping_method' => 'NO',
            'multi_card_name' => 'mastercard,visacard,amexcard,bkash,nagad,rocket',
        ];

        $apiUrl = $isSandbox 
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';

        // Store transaction meta
        $transaction->update(['meta' => $postData]);

        // In production, redirect to SSLCommerz
        // For now, show payment form
        return view('backend.pages.vendors.payment-gateway', [
            'transaction' => $transaction,
            'postData' => $postData,
            'apiUrl' => $apiUrl,
        ]);
    }

    public function paymentSuccess(Request $request)
    {
        $tranId = $request->input('tran_id');
        $transaction = WalletTransaction::where('transaction_id', $tranId)->first();

        if (!$transaction) {
            return redirect()->route('admin.dashboard')->with('error', 'Transaction not found');
        }

        // Verify with SSLCommerz (in production)
        // For now, mark as completed
        $transaction->update([
            'status' => 'completed',
            'balance_after' => $transaction->vendor->wallet_balance + $transaction->amount,
            'meta' => array_merge($transaction->meta ?? [], $request->all()),
        ]);

        $transaction->vendor->increment('wallet_balance', $transaction->amount);

        return redirect()->route('admin.vendors.show', $transaction->vendor)
            ->with('success', 'Payment successful! Wallet recharged with $' . number_format($transaction->amount, 2));
    }

    public function paymentFail(Request $request)
    {
        $tranId = $request->input('tran_id');
        $transaction = WalletTransaction::where('transaction_id', $tranId)->first();

        if ($transaction) {
            $transaction->update(['status' => 'failed']);
        }

        return redirect()->route('admin.vendors.show', $transaction->vendor)
            ->with('error', 'Payment failed. Please try again.');
    }

    public function paymentCancel(Request $request)
    {
        $tranId = $request->input('tran_id');
        $transaction = WalletTransaction::where('transaction_id', $tranId)->first();

        if ($transaction) {
            $transaction->update(['status' => 'failed']);
        }

        return redirect()->route('admin.vendors.show', $transaction->vendor)
            ->with('info', 'Payment cancelled.');
    }
}
