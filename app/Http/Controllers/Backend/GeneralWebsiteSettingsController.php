<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GeneralWebsiteSettingsController extends Controller
{
    public function index()
    {
        // Always get the first settings record (or create if doesn't exist)
        $settings = GeneralSetting::first();
        
        if (!$settings) {
            $settings = GeneralSetting::create([]);
        }
        
        $this->setBreadcrumbTitle(__('General Settings'))
            ->setBreadcrumbIcon('lucide:settings');
        
        return $this->renderViewWithBreadcrumbs('backend.pages.general-settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        // Check if settings already exist
        $settings = GeneralSetting::first();
        
        if ($settings) {
            return $this->update($request, $settings);
        }
        
        return $this->createSettings($request);
    }

    public function update(Request $request, GeneralSetting $generalSetting)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact' => 'nullable|string|max:50',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'other_one' => 'nullable|string|max:255',
            'other_two' => 'nullable|string|max:255',
            'other_three' => 'nullable|string|max:255',
            'other_four' => 'nullable|string|max:255',
            'other_five' => 'nullable|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'fav_icon' => 'nullable|image|mimes:ico,png,jpg,gif,svg|max:1024',
        ]);

        // Handle site logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old file if exists
            if ($generalSetting->site_logo && file_exists(public_path('images/website_settings/' . $generalSetting->site_logo))) {
                unlink(public_path('images/website_settings/' . $generalSetting->site_logo));
            }
            
            $file = $request->file('site_logo');
            $filename = time() . '_logo.' . $file->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('images/website_settings'))) {
                mkdir(public_path('images/website_settings'), 0755, true);
            }
            
            $file->move(public_path('images/website_settings'), $filename);
            $validated['site_logo'] = $filename; // Store only filename
        }

        // Handle fav icon upload
        if ($request->hasFile('fav_icon')) {
            // Delete old file if exists
            if ($generalSetting->fav_icon && file_exists(public_path('images/website_settings/' . $generalSetting->fav_icon))) {
                unlink(public_path('images/website_settings/' . $generalSetting->fav_icon));
            }
            
            $file = $request->file('fav_icon');
            $filename = time() . '_icon.' . $file->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('images/website_settings'))) {
                mkdir(public_path('images/website_settings'), 0755, true);
            }
            
            $file->move(public_path('images/website_settings'), $filename);
            $validated['fav_icon'] = $filename; // Store only filename
        }

        // Update the settings
        $generalSetting->update($validated);

        // Clear cache if you're caching settings
        Cache::forget('general_settings');
        
        return redirect()->route('admin.general_settings.index')
            ->with('success', __('Settings updated successfully'));
    }

    private function createSettings(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact' => 'nullable|string|max:50',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'other_one' => 'nullable|string|max:255',
            'other_two' => 'nullable|string|max:255',
            'other_three' => 'nullable|string|max:255',
            'other_four' => 'nullable|string|max:255',
            'other_five' => 'nullable|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'fav_icon' => 'nullable|image|mimes:ico,png,jpg,gif,svg|max:1024',
        ]);

        // Handle site logo upload
        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $filename = time() . '_logo.' . $file->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('images/website_settings'))) {
                mkdir(public_path('images/website_settings'), 0755, true);
            }
            
            $file->move(public_path('images/website_settings'), $filename);
            $validated['site_logo'] = $filename; // Store only filename
        }

        // Handle fav icon upload
        if ($request->hasFile('fav_icon')) {
            $file = $request->file('fav_icon');
            $filename = time() . '_icon.' . $file->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            if (!file_exists(public_path('images/website_settings'))) {
                mkdir(public_path('images/website_settings'), 0755, true);
            }
            
            $file->move(public_path('images/website_settings'), $filename);
            $validated['fav_icon'] = $filename; // Store only filename
        }

        $settings = GeneralSetting::create($validated);

        return redirect()->route('admin.general_settings.index')
            ->with('success', __('Settings created successfully'));
    }
}