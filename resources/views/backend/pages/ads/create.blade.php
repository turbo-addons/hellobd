<x-layouts.backend-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create New Advertisement</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Advertisement Details</h2>
                
                <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <!-- <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vendor *</label>
                            <select name="vendor_id" class="form-control" required>
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }} (Balance: ${{ number_format($vendor->wallet_balance, 2) }})</option>
                                @endforeach
                            </select>
                        </div> -->

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Content/Description</label>
                            <textarea name="content" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Type *</label>
                                <select name="ad_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="home">Home</option>
                                    <option value="category">Category</option>
                                    <option value="single_post">Single Post</option>
                                    <option value="reporter_post">Reporter Post</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Placement *</label>
                                <select name="placement" class="form-control" required>
                                    <option value="">Select Placement</option>
                                    <option value="full_width_inline_one">Home Full Width Inline One</option>
                                    <option value="full_width_inline_two">Home Full Width Inline Two</option>
                                    <option value="full_width_inline_three">Home Full Width Inline Three</option>
                                    <option value="full_width_inline_four">Home Full Width Inline Four</option>
                                    <option value="full_width_inline_five">Home Full Width Inline Five</option>
                                    <option value="full_width_inline_six">Home Full Width Inline Six</option>
                                    <option value="full_width_inline_seven">Home Full Width Inline Seven</option>
                                    <option value="full_width_inline_eight">Home Full Width Inline Eight</option>
                                    <option value="hero_section_click">Home Hero Section Click</option>
                                    <option value="side_banner_one">Home Side Banner One</option>
                                    <option value="side_banner_two">Home Side Banner Two</option>
                                    <option value="category_side_banner">Category Page Side Banner</option>
                                    <option value="single_page_side_banner">Single Page Side Banner</option>
                                    <option value="single_page_inline_banner">Single Page Full Width Inline Banner</option>
                                    <option value="reporter_page_side_banner">Rporter Page Side Banner</option>
                                    <option value="reporter_page_inline_banner">Rporter Page Full Width Inline Banner</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="pending">Pending Approval</option>
                                <option value="active">Active</option>
                                <option value="paused">Paused</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <!-- <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Billing Model *</label>
                                <select name="billing_model" class="form-control" required>
                                    <option value="">Select Model</option>
                                    <option value="cpc">CPC (Per Click)</option>
                                    <option value="cpm">CPM (Per 1000 Views)</option>
                                    <option value="fixed">Fixed Price</option>
                                </select>
                            </div> -->

                            <!-- <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rate ($) *</label>
                                <input type="number" name="rate" step="0.01" class="form-control" placeholder="Enter rate based on billing model" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Budget ($)</label>
                                <input type="number" name="total_budget" step="0.01" class="form-control" placeholder="Optional: Set spending limit">
                            </div> -->
                        </div>

                        <!-- <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date *</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date *</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div> -->

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <p class="text-xs text-gray-500 mt-1">Recommended: 728x90 for banner, 300x250 for sidebar</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link URL</label>
                            <input type="url" name="link_url" class="form-control" placeholder="Where users go when they click the ad">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sponsored Post (Optional)</label>
                            <select name="post_id" class="form-control">
                                <option value="">Select Post</option>
                                @foreach($posts as $post)
                                <option value="{{ $post->id }}">{{ $post->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Advertisement</button>
                            <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ad Guidelines</h3>
                
                <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
                    <!-- <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Billing Models:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>CPC:</strong> Pay per click (e.g., $0.50 per click)</li>
                            <li><strong>CPM:</strong> Pay per 1000 views (e.g., $2.00 per 1000 views)</li>
                            <li><strong>Fixed:</strong> One-time payment (e.g., $100 for entire duration)</li>
                        </ul>
                    </div> -->

                    <!-- <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Ad Placements:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Header:</strong> Top of every page</li>
                            <li><strong>Sidebar:</strong> Right side of content</li>
                            <li><strong>Footer:</strong> Bottom of every page</li>
                            <li><strong>Content:</strong> Within article content</li>
                            <li><strong>Homepage:</strong> Special homepage sections</li>
                        </ul>
                    </div> -->

                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Image Sizes:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Inline Banner:</strong> 970x90px</li>
                            <li><strong>Hero Section Click Banner:</strong> 357x115px</li>
                            <li><strong>SIde Banner:</strong> 332x416px</li>
                        </ul>
                    </div>
<!-- 
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Performance Tracking</h4>
                        <p>Once created, you can track:</p>
                        <ul class="list-disc list-inside space-y-1 mt-2">
                            <li>Real-time impressions</li>
                            <li>Click-through rates (CTR)</li>
                            <li>Cost per acquisition</li>
                            <li>Revenue generated</li>
                        </ul>
                    </div> -->

                </div>
            </div>
        </div>
    </div>
</x-layouts.backend-layout>
