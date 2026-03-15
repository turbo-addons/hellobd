<x-layouts.backend-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Vendor</h1>
    </div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <form action="{{ route('admin.vendors.update', $vendor) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" value="{{ $vendor->name }}" class="form-control" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" value="{{ $vendor->email }}" class="form-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                <input type="text" name="phone" value="{{ $vendor->phone }}" class="form-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                <input type="url" name="website" value="{{ $vendor->website }}" class="form-control">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                <textarea name="address" class="form-control" rows="2">{{ $vendor->address }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $vendor->description }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Wallet Balance ($)</label>
                <input type="number" name="wallet_balance" value="{{ $vendor->wallet_balance }}" step="0.01" class="form-control" required>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Admin can add/deduct balance for vendor</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                <input type="file" name="logo" class="form-control">
                @if($vendor->logo)
                <img src="{{ asset('storage/' . $vendor->logo) }}" class="mt-2 h-20">
                @endif
            </div>
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ $vendor->is_active ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.wallet.recharge', $vendor) }}" class="btn" style="background: #10b981; color: white;">Recharge Wallet</a>
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
</x-layouts.backend-layout>
