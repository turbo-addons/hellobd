<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::withCount('ads')->latest()->paginate(20);
        return view('backend.pages.vendors.index', compact('vendors'));
    }

    public function show(Vendor $vendor)
    {
        $vendor->load(['ads', 'transactions' => function($q) {
            $q->latest()->take(10);
        }]);
        
        return view('backend.pages.vendors.show', compact('vendor'));
    }

    public function create()
    {
        return view('backend.pages.vendors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'website' => 'nullable|url',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        Vendor::create($data);

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor created successfully');
    }

    public function edit(Vendor $vendor)
    {
        return view('backend.pages.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'website' => 'nullable|url',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'wallet_balance' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $vendor->update($data);

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor updated successfully');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('admin.vendors.index')->with('success', 'Vendor deleted successfully');
    }
}
