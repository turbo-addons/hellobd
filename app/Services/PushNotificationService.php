<?php

namespace App\Services;

use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    private $webPush;

    public function __construct()
    {
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => config('app.url'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ]
        ]);
    }

    public function generateVapidKeys()
    {
        return \Minishlink\WebPush\VAPID::createVapidKeys();
    }

    public function subscribe($endpoint, $p256dhKey, $authKey, $userAgent = null, $ipAddress = null)
    {
        try {
            return PushSubscription::updateOrCreate(
                ['endpoint' => $endpoint],
                [
                    'p256dh_key' => $p256dhKey,
                    'auth_key' => $authKey,
                    'user_agent' => $userAgent,
                    'ip_address' => $ipAddress,
                    'is_active' => true,
                    'last_used_at' => now()
                ]
            );
        } catch (\Exception $e) {
            Log::error('Push subscription failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function unsubscribe($endpoint)
    {
        return PushSubscription::where('endpoint', $endpoint)
            ->update(['is_active' => false]);
    }

    public function sendNotification($title, $body, $url = null, $icon = null, $subscriptions = null)
    {
        if ($subscriptions === null) {
            $subscriptions = PushSubscription::active()->get();
        }

        $payload = json_encode([
            'title' => $title,
            'body' => $body,
            'url' => $url ?? env('FRONTEND_URL', config('app.url')),
            'icon' => $icon ?? asset('icons/icon-192.svg'),
            'badge' => asset('icons/icon-192.svg'),
            'timestamp' => now()->timestamp * 1000,
        ]);

        $results = [];
        $failedEndpoints = [];

        foreach ($subscriptions as $subscription) {
            try {
                $webPushSubscription = Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'keys' => [
                        'p256dh' => $subscription->p256dh_key,
                        'auth' => $subscription->auth_key
                    ]
                ]);

                $result = $this->webPush->sendOneNotification($webPushSubscription, $payload);
                
                if ($result->isSuccess()) {
                    $subscription->update(['last_used_at' => now()]);
                    $results[] = ['success' => true, 'endpoint' => $subscription->endpoint];
                } else {
                    $failedEndpoints[] = $subscription->endpoint;
                    $results[] = [
                        'success' => false, 
                        'endpoint' => $subscription->endpoint,
                        'reason' => $result->getReason()
                    ];
                    
                    // Deactivate invalid subscriptions (expired or deleted)
                    if ($result->isSubscriptionExpired()) {
                        $subscription->update(['is_active' => false]);
                    }
                }
            } catch (\Exception $e) {
                $failedEndpoints[] = $subscription->endpoint;
                $results[] = [
                    'success' => false, 
                    'endpoint' => $subscription->endpoint,
                    'error' => $e->getMessage()
                ];
                Log::error('Push notification failed: ' . $e->getMessage());
            }
        }

        return [
            'total_sent' => count($subscriptions),
            'successful' => count($subscriptions) - count($failedEndpoints),
            'failed' => count($failedEndpoints),
            'results' => $results
        ];
    }

    public function sendBreakingNews($post)
    {
        return $this->sendNotification(
            'Breaking News - HelloBD',
            $post->title,
            env('FRONTEND_URL', config('app.url')) . '/news/' . $post->slug,
            $post->getFirstMediaUrl('featured_image') ?: asset('icons/icon-192.svg')
        );
    }

    public function getActiveSubscriptionsCount()
    {
        return PushSubscription::active()->count();
    }
}