<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Vendor;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        $ads = Advertisement::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

            $stats = [
                'total'   => Advertisement::count(),
                'active'  => Advertisement::where('status', 'active')->count(),
                'pending' => Advertisement::where('status', 'pending')->count(),
                'paused'  => Advertisement::where('status', 'paused')->count(),
                'expired' => Advertisement::where('status', 'expired')->count(),
                'rejected'=> Advertisement::where('status', 'rejected')->count(),
            ];

        // If AJAX, return full page HTML (we’ll extract table in JS)
        if ($request->ajax()) {
            return view('backend.pages.ads.index', compact('ads', 'stats'))->render();
        }

        return view('backend.pages.ads.index', compact('ads', 'stats'));
    }

    public function create()
    {
        $vendors = Vendor::where('is_active', true)->get();
        $posts = Post::where('status', 'published')->latest()->take(100)->get();
        
        return view('backend.pages.ads.create', compact('vendors', 'posts'));
    }

    public function store(Request $request)
    {
        // Only validate fields that come from user input (e.g., title, content, image, link_url, post_id)
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'ad_type' => 'required|string|max:50',
            'placement' => 'required|string|max:150',
            'image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url',
            'post_id' => 'nullable|exists:posts,id',
        ]);

        // Add static/fixed values
        $data['vendor_id'] = 1; // Set your default vendor ID
        $data['billing_model'] = 'cpm'; // static billing model
        $data['rate'] = 100; // static rate
        $data['total_budget'] = 1000; // static total budget
        $data['start_date'] = now(); // static start date
        $data['end_date'] = now()->addMonth(); // static end date
        $data['status'] = 'active';

        // Handle image if uploaded
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/ads'), $imageName);
            $data['image'] = 'images/ads/' . $imageName;
        }

        Advertisement::create($data);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Advertisement created successfully. Awaiting admin approval.');
    }

    public function edit(Advertisement $ad)
    {
        $vendors = Vendor::where('is_active', true)->get();
        $posts = Post::where('status', 'published')->latest()->take(100)->get();
        
        return view('backend.pages.ads.edit', compact('ad', 'vendors', 'posts'));
    }

    public function update(Request $request, Advertisement $ad)
    {
        // Only validate user-input fields
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'ad_type' => 'required|string|max:50',
            'placement' => 'required|string|max:150',
            'image' => 'nullable|image|max:2048',
            'link_url' => 'nullable|url',
            'post_id' => 'nullable|exists:posts,id',
        ]);

        // Add static/fixed values
        $data['vendor_id'] = 1; // default vendor
        $data['billing_model'] = 'cpm'; // static billing model
        $data['rate'] = 100; // static rate
        $data['total_budget'] = 1000; // static total budget
        $data['start_date'] = now(); // static start date
        $data['end_date'] = now()->addMonth(); // static end date
        // Keep status same unless you want to force it
        $data['status'] = $ad->status;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($ad->image && file_exists(public_path($ad->image))) {
                unlink(public_path($ad->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/ads'), $imageName);
            $data['image'] = 'images/ads/' . $imageName;
        }

        $ad->update($data);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Advertisement updated successfully');
    }


    public function destroy(Advertisement $ad)
    {
        // Delete image if exists
        if ($ad->image && file_exists(public_path($ad->image))) {
            unlink(public_path($ad->image));
        }
        
        $ad->delete();
        
        return redirect()->route('admin.ads.index')->with('success', 'Advertisement deleted successfully');
    }

    public function recordImpression(Advertisement $ad)
    {
        $ad->increment('impressions');
        
        if ($ad->billing_model === 'cpm') {
            $cost = ($ad->rate / 1000);
            $ad->increment('spent', $cost);
            $ad->vendor->decrement('wallet_balance', $cost);
        }

        $this->checkAndExpireAd($ad);
        
        return response()->json(['success' => true]);
    }

    public function recordClick(Advertisement $ad)
    {
        $ad->increment('clicks');
        
        if ($ad->billing_model === 'cpc') {
            $ad->increment('spent', $ad->rate);
            $ad->vendor->decrement('wallet_balance', $ad->rate);
        }

        $this->checkAndExpireAd($ad);
        
        return response()->json(['success' => true]);
    }

    private function checkAndExpireAd(Advertisement $ad)
    {
        if ($ad->isExpired() || $ad->isBudgetExceeded()) {
            $ad->update(['status' => 'expired']);
        }
    }
}
