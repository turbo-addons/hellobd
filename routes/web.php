<?php

declare(strict_types=1);

use App\Http\Controllers\Backend\ActionLogController;
use App\Http\Controllers\Backend\AiCommandController;
use App\Http\Controllers\Backend\AiContentController;
use App\Http\Controllers\Backend\CoreUpgradeController;
use App\Http\Controllers\Backend\Auth\ScreenshotGeneratorLoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DuplicateEmailTemplateController;
use App\Http\Controllers\Backend\EditorController;
use App\Http\Controllers\Backend\EmailConnectionController;
use App\Http\Controllers\Backend\EmailSettingController;
use App\Http\Controllers\Backend\EmailTemplateController;
use App\Http\Controllers\Backend\LocaleController;
use App\Http\Controllers\Backend\MediaController;
use App\Http\Controllers\Backend\ModuleController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\SendTestEmailController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\ProfileController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\OnlineVoteController;
use App\Http\Controllers\Backend\GeneralWebsiteSettingsController;
use App\Http\Controllers\Backend\SubscriberController;
use App\Http\Controllers\Backend\ReporterController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\TermController;
use App\Http\Controllers\Backend\TranslationController;
use App\Http\Controllers\Backend\UserLoginAsController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\UnsubscribeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Installation routes
require __DIR__.'/install.php';

// Frontend Routes (Public) - NO AUTH REQUIRED
Route::get('/', function() {
    return app(\App\Http\Controllers\Frontend\HomeController::class)->index();
});

Route::get('/clear-hellobd-cache', function () {

    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');

    return redirect()->back()->with('success', 'Cache cleared successfully!');
})->name('hellobd.cache.clear');

// Route::get('/phpinfo', function () {
//     return response()->json([
//         'pgsql_enabled' => extension_loaded('pgsql'),
//         'pdo_pgsql_enabled' => extension_loaded('pdo_pgsql'),
//     ]);
// });


// Route::get('/db-test', function () {
//     try {
//         $pdo = DB::connection()->getPdo();
//         return response()->json([
//             "status" => "success",
//             "message" => "Database connected successfully",
//             "driver" => $pdo->getAttribute(PDO::ATTR_DRIVER_NAME),
//             "server_version" => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION)
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             "status" => "error",
//             "message" => $e->getMessage(),
//             "driver" => config('database.default'),
//             "host" => config('database.connections.' . config('database.default') . '.host'),
//             "port" => config('database.connections.' . config('database.default') . '.port'),
//             "database" => config('database.connections.' . config('database.default') . '.database')
//         ], 500);
//     }
// })->name('db.test');

Route::get('/news/{slug}', [\App\Http\Controllers\Frontend\NewsController::class, 'show'])->name('news.show');
Route::get('/{category}/{id}', [\App\Http\Controllers\Frontend\NewsController::class, 'showById'])->name('news.show.id')->where(['category' => '[a-z-]+', 'id' => '[0-9]+']);
Route::get('/category/{slug}', [\App\Http\Controllers\Frontend\CategoryController::class, 'show'])->name('category.show');
Route::get('/search', [\App\Http\Controllers\Frontend\SearchController::class, 'index'])->name('search');

// Ad Widget Routes
Route::get('/api/ads/{placement}', [\App\Http\Controllers\Frontend\AdWidgetController::class, 'getAd'])->name('ad.get');
Route::get('/ad/click/{ad}', [\App\Http\Controllers\Frontend\AdWidgetController::class, 'recordClick'])->name('ad.click');

/**
 * Admin routes.
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['web', 'admin', 'auth', 'verified']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RoleController::class);
    Route::delete('roles/delete/bulk-delete', [RoleController::class, 'bulkDelete'])->name('roles.bulk-delete');

    // Reporters Routes.
    Route::get('/reporters', [ReporterController::class, 'index'])->name('reporters.index');
    Route::get('/reporters/create', [ReporterController::class, 'create'])->name('reporters.create');
    Route::post('/reporters', [ReporterController::class, 'store'])->name('reporters.store');
    Route::get('/reporters/{reporter}', [ReporterController::class, 'show'])->name('reporters.show');
    Route::get('/reporters/{reporter}/edit', [ReporterController::class, 'edit'])->name('reporters.edit');
    Route::put('/reporters/{reporter}', [ReporterController::class, 'update'])->name('reporters.update');

    // Online Vote.
    Route::get('/questions', [OnlineVoteController::class, 'questionIndex'])->name('questions.index');
    Route::get('/questions/create', [OnlineVoteController::class, 'questionCreate'])->name('questions.create');
    Route::post('/questions', [OnlineVoteController::class, 'questionStore'])->name('questions.store');
    Route::get('/questions/{question}', [OnlineVoteController::class, 'questionShow'])->name('questions.show');
    Route::get('/questions/{question}/edit', [OnlineVoteController::class, 'questionEdit'])->name('questions.edit');
    Route::put('/questions/{question}', [OnlineVoteController::class, 'questionUpdate'])->name('questions.update');
    Route::delete('/questions/{question}', [OnlineVoteController::class, 'questionDelete'])->name('questions.delete');
    Route::post('/questions/{question}/toggle-status', [OnlineVoteController::class, 'questionToggleStatus'])->name('questions.toggle-status');
    
    //Website General Settings Routes.
    Route::get('/general-settings', [GeneralWebsiteSettingsController::class, 'index'])->name('general_settings.index');
    Route::post('/general-settings', [GeneralWebsiteSettingsController::class, 'store'])->name('general_settings.store'); // For create
    Route::put('/general-settings/{generalSetting}', [GeneralWebsiteSettingsController::class, 'update'])->name('general_settings.update');

    //Newsletter Subscribers Routes.
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.delete');
    // Route::get('/subscribers/create-newsletter', [SubscriberController::class, 'createNewsletter'])->name('subscribers.create-newsletter');
    // Route::post('/subscribers/send-newsletter', [SubscriberController::class, 'sendNewsletter'])->name('subscribers.send-newsletter');

    // Vendors Routes.
    Route::resource('vendors', \App\Http\Controllers\Backend\VendorController::class);
    Route::get('vendors/{vendor}/recharge', [\App\Http\Controllers\Backend\WalletController::class, 'recharge'])->name('wallet.recharge');
    Route::post('vendors/{vendor}/initiate-payment', [\App\Http\Controllers\Backend\WalletController::class, 'initiatePayment'])->name('wallet.initiate-payment');

    // Ads Routes.
    Route::resource('ads', \App\Http\Controllers\Backend\AdvertisementController::class);
    Route::post('ads/{ad}/impression', [\App\Http\Controllers\Backend\AdvertisementController::class, 'recordImpression'])->name('ads.impression');
    Route::post('ads/{ad}/click', [\App\Http\Controllers\Backend\AdvertisementController::class, 'recordClick'])->name('ads.click');

    // Billing Report Routes.
    Route::get('billing', [\App\Http\Controllers\Backend\BillingReportController::class, 'index'])->name('billing.index');

    // Permissions Routes.
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');

    // Modules Routes.
    Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
    Route::get('/modules/upload', [ModuleController::class, 'upload'])->name('modules.upload');
    Route::get('/modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
    Route::post('/modules/toggle-status/{module}', [ModuleController::class, 'toggleStatus'])->name('modules.toggle-status');
    Route::post('/modules/bulk-activate', [ModuleController::class, 'bulkActivate'])->name('modules.bulk-activate');
    Route::post('/modules/bulk-deactivate', [ModuleController::class, 'bulkDeactivate'])->name('modules.bulk-deactivate');
    Route::post('/modules/store', [ModuleController::class, 'store'])->name('modules.store');
    Route::post('/modules/upload-ajax', [ModuleController::class, 'uploadAjax'])->name('modules.upload-ajax');
    Route::post('/modules/replace', [ModuleController::class, 'replaceModule'])->name('modules.replace');
    Route::post('/modules/cancel-replacement', [ModuleController::class, 'cancelReplacement'])->name('modules.cancel-replacement');
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.delete');

    Route::group(['prefix' => 'settings'], function () {
        // Settings Routes.
        Route::get('/', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/', [SettingController::class, 'store'])->name('settings.store');

        // Email Settings Management Routes.
        Route::get('emails', [EmailSettingController::class, 'index'])->name('email-settings.index');
        Route::post('emails', [EmailSettingController::class, 'update'])->name('email-settings.update');
        Route::post('emails/send-test', [SendTestEmailController::class, 'sendTestEmail'])->name('emails.send-test');

        // Email Connections Management Routes.
        Route::group(['prefix' => 'email-connections', 'as' => 'email-connections.'], function () {
            Route::get('/', [EmailConnectionController::class, 'index'])->name('index');
            Route::post('/', [EmailConnectionController::class, 'store'])->name('store');
            Route::get('providers', [EmailConnectionController::class, 'getProviders'])->name('providers');
            Route::get('providers/{providerType}', [EmailConnectionController::class, 'getProviderFields'])->name('providers.fields');
            Route::get('{email_connection}', [EmailConnectionController::class, 'show'])->name('show');
            Route::put('{email_connection}', [EmailConnectionController::class, 'update'])->name('update');
            Route::delete('{email_connection}', [EmailConnectionController::class, 'destroy'])->name('destroy');
            Route::post('{email_connection}/test', [EmailConnectionController::class, 'testConnection'])->name('test');
            Route::post('{email_connection}/default', [EmailConnectionController::class, 'setDefault'])->name('default');
            Route::post('reorder', [EmailConnectionController::class, 'reorder'])->name('reorder');
        });

        // Email Templates Management Routes.
        Route::group(['prefix' => 'email-templates', 'as' => 'email-templates.'], function () {
            // List and view routes.
            Route::get('/', [EmailTemplateController::class, 'index'])->name('index');
            Route::get('{email_template}', [EmailTemplateController::class, 'show'])->name('show')->where('email_template', '[0-9]+');
            Route::delete('{email_template}', [EmailTemplateController::class, 'destroy'])->name('destroy')->where('email_template', '[0-9]+');

            // API routes for AJAX/JS.
            Route::get('api/list', [EmailTemplateController::class, 'apiList'])->name('api.list');

            // Utility routes.
            Route::get('by-type/{type}', [EmailTemplateController::class, 'getByType'])->name('by-type');
            Route::get('{email_template}/content', [EmailTemplateController::class, 'getContent'])->name('content')->where('email_template', '[0-9]+');
            Route::post('{email_template}/duplicate', [DuplicateEmailTemplateController::class, 'store'])->name('duplicate');

            // Email Builder Routes.
            Route::get('create', [EmailTemplateController::class, 'builder'])->name('create');
            Route::get('{email_template}/edit', [EmailTemplateController::class, 'builderEdit'])->name('edit')->where('email_template', '[0-9]+');
            Route::post('/', [EmailTemplateController::class, 'builderStore'])->name('store');
            Route::put('{email_template}', [EmailTemplateController::class, 'builderUpdate'])->name('update')->where('email_template', '[0-9]+');
            Route::post('upload-image', [EmailTemplateController::class, 'uploadImage'])->name('upload-image');
            Route::post('upload-video', [EmailTemplateController::class, 'uploadVideo'])->name('upload-video');
        });

        // Notifications Management Routes.
        Route::resource('notifications', NotificationController::class);

        // Core Upgrades Routes.
        Route::prefix('core-upgrades')->as('core-upgrades.')->group(function () {
            Route::get('/', [CoreUpgradeController::class, 'index'])->name('index');
            Route::post('/check', [CoreUpgradeController::class, 'checkUpdates'])->name('check');
            Route::post('/upgrade', [CoreUpgradeController::class, 'upgrade'])->name('upgrade');
            Route::post('/upload', [CoreUpgradeController::class, 'uploadUpgrade'])->name('upload');
            Route::post('/backup', [CoreUpgradeController::class, 'createBackup'])->name('backup');
            Route::get('/download/{filename}', [CoreUpgradeController::class, 'downloadBackup'])->name('download');
            Route::post('/restore', [CoreUpgradeController::class, 'restore'])->name('restore');
            Route::post('/delete-backup', [CoreUpgradeController::class, 'deleteBackup'])->name('delete-backup');
            Route::get('/update-status', [CoreUpgradeController::class, 'getUpdateStatus'])->name('update-status');
        });
    });

    // Translation Routes.
    Route::get('/translations', [TranslationController::class, 'index'])->name('translations.index');
    Route::post('/translations', [TranslationController::class, 'update'])->name('translations.update');
    Route::post('/translations/create', [TranslationController::class, 'create'])->name('translations.create');

    // Login as & Switch back.
    Route::resource('users', UserController::class);
    Route::delete('users/delete/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::get('users/{id}/login-as', [UserLoginAsController::class, 'loginAs'])->name('users.login-as');
    Route::post('users/switch-back', [UserLoginAsController::class, 'switchBack'])->name('users.switch-back');

    // Action Log Routes.
    Route::get('/action-log', [ActionLogController::class, 'index'])->name('actionlog.index');

    // Posts/Pages Routes - Dynamic post types.
    Route::get('/posts/{postType?}', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{postType}/{post}', [PostController::class, 'show'])->name('posts.show')->where('post', '[0-9]+');
    Route::delete('/posts/{postType}/{post}', [PostController::class, 'destroy'])->name('posts.destroy')->where('post', '[0-9]+');
    Route::delete('/posts/{postType}/delete/bulk-delete', [PostController::class, 'bulkDelete'])->name('posts.bulk-delete');

    // Post Builder Routes (LaraBuilder-based editing - now default for create/edit).
    Route::get('/posts/{postType}/create', [PostController::class, 'builderCreate'])->name('posts.create');
    Route::get('/posts/{postType}/{post}/edit', [PostController::class, 'builderEdit'])->name('posts.edit')->where('post', '[0-9]+');
    Route::post('/posts/{postType}', [PostController::class, 'builderStore'])->name('posts.store');
    Route::put('/posts/{postType}/{post}', [PostController::class, 'builderUpdate'])->name('posts.update')->where('post', '[0-9]+');
    Route::post('/posts/{postType}/upload-image', [PostController::class, 'uploadImage'])->name('posts.upload-image');
    Route::post('/posts/{postType}/upload-video', [PostController::class, 'uploadVideo'])->name('posts.upload-video');

    // Terms Routes (Categories, Tags, etc.).
    Route::get('/terms/{taxonomy}', [TermController::class, 'index'])->name('terms.index');
    Route::get('/terms/{taxonomy}/{term}/edit', [TermController::class, 'edit'])->name('terms.edit');
    Route::post('/terms/{taxonomy}', [TermController::class, 'store'])->name('terms.store');
    Route::put('/terms/{taxonomy}/{term}', [TermController::class, 'update'])->name('terms.update');
    Route::delete('/terms/{taxonomy}/{term}', [TermController::class, 'destroy'])->name('terms.destroy');
    Route::delete('/terms/{taxonomy}/delete/bulk-delete', [TermController::class, 'bulkDelete'])->name('terms.bulk-delete');
    //Other Category Sort Routes For Menu Order
    Route::get('/category-sort', [TermController::class, 'categorySortIndex'])->name('category.sort.index');
    Route::post('/category-sort/save', [TermController::class, 'categorySortsave'])->name('category.sort.save');
    //Main Category Sort Routes For Menu Order
    Route::get('maincategory-sort', [TermController::class, 'mainCategorySortIndex'])->name('maincategory.sort.index');
    Route::post('maincategory-sort/save', [TermController::class, 'mainCategorySortSave'])->name('maincategory.sort.save');

    // Media Routes.
    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::get('/api', [MediaController::class, 'api'])->name('api');
        Route::post('/', [MediaController::class, 'store'])->name('store')->middleware('check.upload.limits');
        Route::get('/upload-limits', [MediaController::class, 'getUploadLimits'])->name('upload-limits');
        Route::delete('/{id}', [MediaController::class, 'destroy'])->name('destroy');
        Route::delete('/', [MediaController::class, 'bulkDelete'])->name('bulk-delete');
    });

    // Editor Upload Route.
    Route::post('/editor/upload', [EditorController::class, 'upload'])->name('editor.upload');

    // AI Content Generation Routes.
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/providers', [AiContentController::class, 'getProviders'])->name('providers');
        Route::post('/generate-content', [AiContentController::class, 'generateContent'])->name('generate-content');
        Route::post('/modify-text', [AiContentController::class, 'modifyText'])->name('modify-text');

        // AI Command System (Agentic CMS).
        Route::get('/command/status', [AiCommandController::class, 'status'])->name('command.status');
        Route::get('/command/examples', [AiCommandController::class, 'examples'])->name('command.examples');
        Route::post('/command/process', [AiCommandController::class, 'process'])->name('command.process');
        Route::post('/command/process-stream', [AiCommandController::class, 'processStream'])->name('command.process-stream');
    });
});

/**
 * Profile routes.
 */
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::put('/update-additional', [ProfileController::class, 'updateAdditional'])->name('update.additional');
});

Route::get('/locale/{lang}', [LocaleController::class, 'switch'])->name('locale.switch');
Route::get('/screenshot-login/{email}', [ScreenshotGeneratorLoginController::class, 'login'])->middleware('web')->name('screenshot.login');
Route::get('/demo-preview', fn () => view('demo.preview'))->name('demo.preview');

// Email Unsubscribe Routes
Route::prefix('unsubscribe')->name('unsubscribe.')->group(function () {
    Route::get('/{encryptedEmail}', [UnsubscribeController::class, 'unsubscribe'])->name('process');
    Route::get('/confirm/{encryptedEmail}', [UnsubscribeController::class, 'confirm'])->name('confirm');
    Route::post('/process/{encryptedEmail}', [UnsubscribeController::class, 'processConfirmed'])->name('confirmed');
});

// Payment Gateway Routes
Route::get('/payment/success', [\App\Http\Controllers\Backend\WalletController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/fail', [\App\Http\Controllers\Backend\WalletController::class, 'paymentFail'])->name('payment.fail');
Route::get('/payment/cancel', [\App\Http\Controllers\Backend\WalletController::class, 'paymentCancel'])->name('payment.cancel');

// CDN Image Proxy Route - Bypass CORS issues
Route::get('/cdn-proxy', function (\Illuminate\Http\Request $request) {
    $url = $request->query('url');
    
    if (!$url) {
        abort(400, 'Image URL missing');
    }
    
    // Only allow CDN domain for security
    $allowedDomain = config('filesystems.disks.r2.url');
    if (!str_starts_with($url, $allowedDomain)) {
        abort(403, 'Unauthorized domain');
    }
    
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(10)->get($url);
        
        if (!$response->successful()) {
            abort(404, 'Image not found');
        }
        
        return response($response->body())
            ->header('Content-Type', $response->header('Content-Type') ?? 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=86400')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, HEAD, OPTIONS')
            ->header('Access-Control-Allow-Headers', '*');
            
    } catch (\Exception $e) {
        \Log::error('CDN Proxy Error: ' . $e->getMessage());
        abort(500, 'Image fetch failed');
    }
})->name('cdn.proxy');

// Simple timezone check route - No post creation
Route::get('/debug/check-time', function () {
    try {
        // Laravel now() time
        $laravelNow = now();
        
        // PostgreSQL server time
        $dbTime = \DB::selectOne("SELECT NOW() as server_time");
        $dbTimezone = \DB::selectOne("SHOW TIMEZONE");
        
        return response()->json([
            'laravel_info' => [
                'now()' => $laravelNow->toDateTimeString(),
                'timezone' => config('app.timezone'),
                'php_timezone' => date_default_timezone_get(),
            ],
            'postgresql_info' => [
                'server_time' => $dbTime->server_time ?? null,
                'timezone' => $dbTimezone->timezone ?? 'N/A',
            ],
            'comparison' => [
                'laravel_time' => $laravelNow->toDateTimeString(),
                'database_time' => $dbTime->server_time ?? null,
                'time_difference' => 'Check if both times match',
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ], 500);
    }
});

// Last Post Route - Shows title and timestamps in database format
Route::get('/last-post', function () {
    $lastPost = \DB::table('posts')
        ->select('title', 'created_at', 'updated_at', 'published_at')
        ->orderBy('id', 'desc')
        ->first();
    
    if (!$lastPost) {
        return response()->json([
            'success' => false,
            'message' => 'No posts found'
        ], 404);
    }
    
    return response()->json([
        'success' => true,
        'data' => [
            'title' => $lastPost->title,
            'created_at' => $lastPost->created_at,
            'updated_at' => $lastPost->updated_at,
            'published_at' => $lastPost->published_at,
            'current_time_now' => now(),
            'current_time_utc' => now()->utc(),
        ]
    ]);
});

