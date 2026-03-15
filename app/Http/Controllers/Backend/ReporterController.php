<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Reporter;
use App\Models\User;
use Illuminate\Http\Request;

class ReporterController extends Controller
{
    public function index()
{
    $reporters = Reporter::select(['id', 'type', 'desk_name', 'user_id', 'verification_status', 'rating', 'rating_count', 'experience', 'specialization', 'total_articles', 'created_at'])
        ->with('user:id,first_name,last_name,username,email')
        ->withCount('posts')
        ->when(request('search'), function($query, $search) {
            $query->where(function($q) use ($search) {
                $q->where('desk_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%")
                               ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        })
        ->when(request('status'), function($query, $status) {
            $query->where('verification_status', $status);
        })
        ->paginate(15);
    
    // Cache statistics for 5 minutes
    $stats = cache()->remember('reporter_stats', 300, function () {
        return [
            'total' => Reporter::count(),
            'verified' => Reporter::where('verification_status', 'verified')->count(),
            'avg_rating' => Reporter::avg('rating') ?? 0,
            'top_rated' => Reporter::where('rating', '>=', 4)->count(),
        ];
    });
    
    $this->setBreadcrumbTitle(__('Reporters'))
        ->setBreadcrumbIcon('lucide:users');
    
    return $this->renderViewWithBreadcrumbs('backend.pages.reporters.index', compact('reporters', 'stats'));
}

    public function create()
    {
        $users = \App\Models\User::all();
        
        $this->setBreadcrumbTitle(__('Add Reporter'))
            ->setBreadcrumbIcon('lucide:user-plus')
            ->addBreadcrumbItem(__('Reporters'), route('admin.reporters.index'));
        
        return $this->renderViewWithBreadcrumbs('backend.pages.reporters.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:human,desk',
            'user_id' => 'required_if:type,human|nullable|exists:users,id',
            'desk_name' => 'required_if:type,desk|nullable|string',
            'designation' => 'nullable|string',
            'bio' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        if ($validated['type'] === 'desk') {

            // Help Desk user (ID 566) find করুন
            $helpDeskUser = \App\Models\User::find(566);
            
            if (!$helpDeskUser) {
                return back()->withErrors(['error' => __('No user found in the system. Please create a user first.')]);
            }
        
            $validated['user_id'] = $helpDeskUser->id;
            $validated['slug'] = \Str::slug($validated['desk_name']);
        } else {
            // Human type: fetch user first
            $user = User::find($validated['user_id']);
            if ($user) {
                // first_name + last_name
                $validated['desk_name'] = trim($user->first_name . ' ' . $user->last_name);
            } else {
                // fallback
                $validated['desk_name'] = 'Unknown Reporter';
            }
            $validated['slug'] = \Str::slug('reporter-' . $validated['user_id']);
        }
        
        if (!empty($validated['location'])) {
            $validated['location_updated_at'] = now();
        }
        
        $reporter = Reporter::create($validated);

        if ($request->hasFile('photo')) {
            $reporter->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        return redirect()->route('admin.reporters.index')->with('success', __('Reporter created successfully'));
    }

    public function show(Reporter $reporter)
    {
        $reporter->load(['user', 'posts']);
        
        $this->setBreadcrumbTitle($reporter->display_name)
            ->setBreadcrumbIcon('lucide:user')
            ->addBreadcrumbItem(__('Reporters'), route('admin.reporters.index'));
        
        return $this->renderViewWithBreadcrumbs('backend.pages.reporters.show', compact('reporter'));
    }

    public function edit(Reporter $reporter)
    {
        $users = \App\Models\User::all();
        
        $this->setBreadcrumbTitle(__('Edit Reporter'))
            ->setBreadcrumbIcon('lucide:edit')
            ->addBreadcrumbItem(__('Reporters'), route('admin.reporters.index'));
        
        return $this->renderViewWithBreadcrumbs('backend.pages.reporters.edit', compact('reporter', 'users'));
    }

    public function update(Request $request, Reporter $reporter)
    {
        $validated = $request->validate([
            'type' => 'required|in:human,desk',
            'desk_name' => 'required_if:type,desk',
            'user_id' => 'nullable|exists:users,id',
            'designation' => 'nullable|string',
            'age' => 'nullable|integer|min:18|max:100',
            'location' => 'nullable|string',
            'experience' => 'nullable|string',
            'specialization' => 'nullable|string',
            'bio' => 'nullable|string',
            'social_media' => 'nullable|array',
            'social_media.facebook' => 'nullable|url',
            'social_media.twitter' => 'nullable|url',
            'social_media.linkedin' => 'nullable|url',
            'social_media.instagram' => 'nullable',
            'social_media.youtube' => 'nullable|url',
            'verification_status' => 'required|in:pending,verified,rejected',
        ]);

         // যদি type change হয় desk এ, তাহলে user_id set করুন
        if ($validated['type'] === 'desk') {
            // Desk হলে সবসময় Help Desk user (566)
            $helpDeskUser = \App\Models\User::find(566);
    
            if (!$helpDeskUser) {
                return back()->withErrors(['error' => __('Help desk user not found.')]);
            }
    
            $validated['user_id'] = $helpDeskUser->id;
            $validated['slug'] = \Str::slug($validated['desk_name']);
        } else {
            // Human type: fetch user first
            $user = User::find($validated['user_id']);
            if ($user) {
                // first_name + last_name
                $validated['desk_name'] = trim($user->first_name . ' ' . $user->last_name);
            } else {
                // fallback
                $validated['desk_name'] = 'Unknown Reporter';
            }
            // Human হলে form থেকে আসা user_id
            $validated['slug'] = \Str::slug('reporter-' . $validated['user_id']);
        }

        if (!empty($validated['location']) && $validated['location'] !== $reporter->location) {
            $validated['location_updated_at'] = now();
        }

        $reporter->update($validated);

        if ($request->hasFile('photo')) {
            $reporter->clearMediaCollection('photo');
            $reporter->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        return redirect()->route('admin.reporters.index')->with('success', __('Reporter updated successfully'));
    }
}