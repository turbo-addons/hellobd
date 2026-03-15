<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;

class SubscriberController extends Controller
{

    public function index(Request $request)
    {
        // Query subscribers with optional status filter & search
        $subscribers = Subscriber::select(['id', 'email', 'status', 'confirmation_token', 'created_at'])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Cache statistics for 5 minutes
        $stats = cache()->remember('subscriber_stats', 300, function () {
            return [
                'total' => Subscriber::count(),
                'pending' => Subscriber::where('status', 'pending')->count(),
                'subscribed' => Subscriber::where('status', 'subscribed')->count(),
                'unsubscribed' => Subscriber::where('status', 'unsubscribed')->count(),
            ];
        });

        // Set breadcrumbs
        $this->setBreadcrumbTitle(__('Subscribers'))
            ->setBreadcrumbIcon('lucide:mail'); // Newsletter icon

        // Render view with breadcrumbs
        return $this->renderViewWithBreadcrumbs('backend.pages.subscribers.index', compact('subscribers', 'stats'));
    }

    /**
     * Delete a subscriber
     */
    public function destroy(Subscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.subscribers.index')
            ->with('success', 'Subscriber deleted successfully.');
    }
}
