<x-layouts.backend-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Advertisement</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('admin.ads.update', $ad) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Title *</label>
                    <input type="text" name="title" value="{{ $ad->title }}" class="form-control" required>
                </div>

                <!-- <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vendor *</label>
                    <select name="vendor_id" class="form-control" required>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}" {{ $ad->vendor_id == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->name }} (Balance: ${{ number_format($vendor->wallet_balance, 2) }})
                        </option>
                        @endforeach
                    </select>
                </div> -->

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Content/Description</label>
                    <textarea name="content" class="form-control" rows="3">{{ $ad->content }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Type *</label>
                        <select name="ad_type" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="home" {{ $ad->ad_type == 'home' ? 'selected' : '' }}>Home</option>
                            <option value="category" {{ $ad->ad_type == 'category' ? 'selected' : '' }}>Category</option>
                            <option value="single_post" {{ $ad->ad_type == 'single_post' ? 'selected' : '' }}>Single Post</option>
                            <option value="reporter_post" {{ $ad->ad_type == 'reporter_post' ? 'selected' : '' }}>Reporter Post</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Placement *</label>
                        <select name="placement" class="form-control" required>
                            <option value="">Select Placement</option>
                            <option value="full_width_inline_one" {{ $ad->placement == 'full_width_inline_one' ? 'selected' : '' }}>Home Full Width Inline One</option>
                            <option value="full_width_inline_two" {{ $ad->placement == 'full_width_inline_two' ? 'selected' : '' }}>Home Full Width Inline Two</option>
                            <option value="full_width_inline_three" {{ $ad->placement == 'full_width_inline_three' ? 'selected' : '' }}>Home Full Width Inline Three</option>
                            <option value="full_width_inline_four" {{ $ad->placement == 'full_width_inline_four' ? 'selected' : '' }}>Home Full Width Inline Four</option>
                            <option value="full_width_inline_five" {{ $ad->placement == 'full_width_inline_five' ? 'selected' : '' }}>Home Full Width Inline Five</option>
                            <option value="full_width_inline_six" {{ $ad->placement == 'full_width_inline_six' ? 'selected' : '' }}>Home Full Width Inline Six</option>
                            <option value="full_width_inline_seven" {{ $ad->placement == 'full_width_inline_seven' ? 'selected' : '' }}>Home Full Width Inline Seven</option>
                            <option value="full_width_inline_eight" {{ $ad->placement == 'full_width_inline_eight' ? 'selected' : '' }}>Home Full Width Inline Eight</option>
                            <option value="hero_section_click" {{ $ad->placement == 'hero_section_click' ? 'selected' : '' }}>Home Hero Section Click</option>
                            <option value="side_banner_one" {{ $ad->placement == 'side_banner_one' ? 'selected' : '' }}>Home Side Banner One</option>
                            <option value="side_banner_two" {{ $ad->placement == 'side_banner_two' ? 'selected' : '' }}>Home Side Banner Two</option>
                            <option value="category_side_banner" {{ $ad->placement == 'category_side_banner' ? 'selected' : '' }}>Category Page Side Banner</option>
                            <option value="single_page_side_banner" {{ $ad->placement == 'single_page_side_banner' ? 'selected' : '' }}>Single Page Side Banner</option>
                            <option value="single_page_inline_banner" {{ $ad->placement == 'single_page_inline_banner' ? 'selected' : '' }}>Single Page Full Width Inline Banner</option>
                            <option value="reporter_page_side_banner" {{ $ad->placement == 'reporter_page_side_banner' ? 'selected' : '' }}>Reporter Page Side Banner</option>
                            <option value="reporter_page_inline_banner" {{ $ad->placement == 'reporter_page_inline_banner' ? 'selected' : '' }}>Rporter Page Full Width Inline Banner</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="pending" {{ $ad->status == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="active" {{ $ad->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="paused" {{ $ad->status == 'paused' ? 'selected' : '' }}>Paused</option>
                        <option value="expired" {{ $ad->status == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="rejected" {{ $ad->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing Model *</label>
                        <select name="billing_model" class="form-control" required>
                            <option value="cpc" {{ $ad->billing_model == 'cpc' ? 'selected' : '' }}>CPC (Per Click)</option>
                            <option value="cpm" {{ $ad->billing_model == 'cpm' ? 'selected' : '' }}>CPM (Per 1000 Views)</option>
                            <option value="fixed" {{ $ad->billing_model == 'fixed' ? 'selected' : '' }}>Fixed Price</option>
                        </select>
                    </div> -->

                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rate ($) *</label>
                        <input type="number" name="rate" value="{{ $ad->rate }}" step="0.01" class="form-control" required>
                    </div> -->

                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Budget ($)</label>
                        <input type="number" name="total_budget" value="{{ $ad->total_budget }}" step="0.01" class="form-control">
                    </div> -->
                </div>

                <!-- <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date *</label>
                        <input type="date" name="start_date" value="{{ $ad->start_date->format('Y-m-d') }}" class="form-control" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date *</label>
                        <input type="date" name="end_date" value="{{ $ad->end_date->format('Y-m-d') }}" class="form-control" required>
                    </div>
                </div> -->

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if($ad->image)
                    <img src="{{ asset($ad->image) }}" class="mt-2 h-20">
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link URL</label>
                    <input type="url" name="link_url" value="{{ $ad->link_url }}" class="form-control">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sponsored Post (Optional)</label>
                    <select name="post_id" class="form-control">
                        <option value="">Select Post</option>
                        @foreach($posts as $post)
                        <option value="{{ $post->id }}" {{ $ad->post_id == $post->id ? 'selected' : '' }}>{{ $post->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Performance Stats</h3>
                    <div class="grid grid-cols-4 gap-4 text-sm">
                        <div>
                            <div class="text-gray-500 dark:text-gray-400">Impressions</div>
                            <div class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($ad->impressions) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 dark:text-gray-400">Clicks</div>
                            <div class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($ad->clicks) }}</div>
                        </div>
                        <div>
                            <div class="text-gray-500 dark:text-gray-400">CTR</div>
                            <div class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($ad->ctr, 2) }}%</div>
                        </div>
                        <div>
                            <div class="text-gray-500 dark:text-gray-400">Spent</div>
                            <div class="text-lg font-bold text-gray-900 dark:text-white">${{ number_format($ad->spent, 2) }}</div>
                        </div>
                    </div>
                </div> -->

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Advertisement</button>
                    <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</x-layouts.backend-layout>
