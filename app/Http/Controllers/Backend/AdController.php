<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::with('vendor')->latest()->get();
        return view('backend.pages.ads.index', compact('ads'));
    }

    public function create()
    {
        $vendors = Vendor::where('is_active', true)->get();
        return view('backend.pages.ads.create', compact('vendors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vendor_id' => 'nullable|exists:vendors,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:image,code,video',
            'placement' => 'required|in:header,sidebar,footer,content_top,content_bottom,popup',
            'content' => 'nullable|string',
            'link_url' => 'nullable|url',
            'dimensions' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'boolean',
            'priority' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('ads', 'public');
        }

        Ad::create($data);
        return redirect()->route('admin.ads.index')->with('success', 'Ad created successfully');
    }

    public function edit(Ad $ad)
    {
        $vendors = Vendor::where('is_active', true)->get();
        return view('backend.pages.ads.edit', compact('ad', 'vendors'));
    }

    public function update(Request $request, Ad $ad)
    {
        $data = $request->validate([
            'vendor_id' => 'nullable|exists:vendors,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:image,code,video',
            'placement' => 'required|in:header,sidebar,footer,content_top,content_bottom,popup',
            'content' => 'nullable|string',
            'link_url' => 'nullable|url',
            'dimensions' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'boolean',
            'priority' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('ads', 'public');
        }

        $ad->update($data);
        return redirect()->route('admin.ads.index')->with('success', 'Ad updated successfully');
    }

    public function destroy(Ad $ad)
    {
        $ad->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Ad deleted successfully');
    }
}
