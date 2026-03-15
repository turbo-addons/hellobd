<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PushNotificationController extends Controller
{
    private $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    public function getVapidPublicKey()
    {
        return response()->json([
            'success' => true,
            'public_key' => env('VAPID_PUBLIC_KEY')
        ]);
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint' => 'required|string|max:500',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $subscription = $this->pushService->subscribe(
                $request->input('endpoint'),
                $request->input('keys.p256dh'),
                $request->input('keys.auth'),
                $request->header('User-Agent'),
                $request->ip()
            );

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to push notifications',
                'subscription_id' => $subscription->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to subscribe to push notifications',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'endpoint' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->pushService->unsubscribe($request->input('endpoint'));

            return response()->json([
                'success' => true,
                'message' => 'Successfully unsubscribed from push notifications',
                'affected_rows' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unsubscribe from push notifications',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function sendTestNotification(Request $request)
    {
        // If GET request, send default test notification
        if ($request->isMethod('get')) {
            try {
                $result = $this->pushService->sendNotification(
                    'টেস্ট নোটিফিকেশন',
                    'এটি একটি পরীক্ষামূলক নোটিফিকেশন। আপনার সাবস্ক্রিপশন সফলভাবে কাজ করছে!',
                    'https://hellobd.news'
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Test notification sent to all subscribers',
                    'result' => $result
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send test notification',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
        }

        // POST request with custom data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:500',
            'url' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->pushService->sendNotification(
                $request->input('title'),
                $request->input('body'),
                $request->input('url')
            );

            return response()->json([
                'success' => true,
                'message' => 'Test notification sent',
                'result' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function sendBreakingNews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $post = \App\Models\Post::with(['media'])->findOrFail($request->post_id);
            
            $result = $this->pushService->sendBreakingNews($post);

            return response()->json([
                'success' => true,
                'message' => 'Breaking news notification sent successfully',
                'result' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send breaking news notification',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function getStats()
    {
        try {
            $activeSubscriptions = $this->pushService->getActiveSubscriptionsCount();

            return response()->json([
                'success' => true,
                'stats' => [
                    'active_subscriptions' => $activeSubscriptions,
                    'vapid_configured' => !empty(env('VAPID_PUBLIC_KEY'))
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get push notification stats',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}