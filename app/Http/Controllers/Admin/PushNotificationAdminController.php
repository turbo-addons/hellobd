<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PushNotificationService;
use App\Models\Post;
use App\Models\PushSubscription;
use Illuminate\Http\Request;

class PushNotificationAdminController extends Controller
{
    private $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    public function sendTestNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:500',
            'url' => 'nullable|url'
        ]);

        try {
            $result = $this->pushService->sendNotification(
                $request->title,
                $request->body,
                $request->url
            );

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent successfully',
                'result' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendBreakingNews(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id'
        ]);

        try {
            $post = Post::with(['media'])->findOrFail($request->post_id);
            $result = $this->pushService->sendBreakingNews($post);

            return response()->json([
                'success' => true,
                'message' => 'Breaking news notification sent successfully',
                'result' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send breaking news: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStats()
    {
        try {
            $stats = [
                'total_subscriptions' => PushSubscription::count(),
                'active_subscriptions' => PushSubscription::active()->count(),
                'vapid_configured' => !empty(env('VAPID_PUBLIC_KEY'))
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get stats: ' . $e->getMessage()
            ], 500);
        }
    }
}