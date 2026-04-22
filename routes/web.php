<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboardController;
use App\Http\Controllers\Merchant\ProductController as MerchantProductController;
use App\Http\Controllers\Admin\MerchantController;
use App\Http\Controllers\Merchant\OrderController as MerchantOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Merchant\SettingsController as MerchantSettingsController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\TrafficController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Merchant\NotificationController as MerchantNotificationController;
use App\Http\Controllers\Admin\StoreBannerController;
use App\Http\Controllers\Admin\StoreSectionController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Merchant\CategoryController as MerchantCategoryController;



Route::get('/sitemap.xml', function () {
    $sitemap = \Spatie\Sitemap\Sitemap::create()
        ->add(\Spatie\Sitemap\Tags\Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency('daily'))
        ->add(\Spatie\Sitemap\Tags\Url::create('/products')
            ->setPriority(0.9)
            ->setChangeFrequency('daily'))
        ->add(\Spatie\Sitemap\Tags\Url::create('/toko/taku-official')
            ->setPriority(0.8)
            ->setChangeFrequency('weekly'));

    \App\Models\Product::where('is_active', true)->get()
        ->each(fn($p) => $sitemap->add(
            \Spatie\Sitemap\Tags\Url::create('/product/' . $p->id)
                ->setPriority(0.8)
                ->setChangeFrequency('weekly')
                ->setLastModificationDate($p->updated_at)
        ));

    \App\Models\Store::where('status', 'active')->get()
        ->each(fn($s) => $sitemap->add(
            \Spatie\Sitemap\Tags\Url::create('/toko/' . $s->slug)
                ->setPriority(0.7)
                ->setChangeFrequency('weekly')
        ));

    return response($sitemap->render(), 200)
        ->header('Content-Type', 'application/xml');
});

// ── Language
Route::get('/lang/{lang}', [LanguageController::class, 'switch'])->name('lang.switch');

// ── Public
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'shop'])->name('products');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::get('/toko/taku-official', [StoreController::class, 'showOfficial'])->name('store.official');
Route::get('/toko/{slug}', [StoreController::class, 'show'])->name('store.show');

// ── Email verification
Route::get('/email/verify', function () {
    $user      = auth()->user();
    $sisaDetik = 0;
    if ($user && $user->email_resend_at) {
        $sudahBerlalu = now()->timestamp - $user->email_resend_at->timestamp;
        $sisaDetik    = max(0, (30 * 60) - $sudahBerlalu);
    }
    return view('auth.verify-email', compact('sisaDetik'));
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('home')->with('success', 'Email berhasil diverifikasi! Selamat berbelanja.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $user = $request->user();
    if ($user->hasVerifiedEmail()) return redirect()->route('home');
    $cooldownDetik = 30 * 60;
    if ($user->email_resend_at) {
        $sudahBerlalu = now()->timestamp - $user->email_resend_at->timestamp;
        if ($sudahBerlalu < $cooldownDetik) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Kamu baru saja mengirim verifikasi. Tunggu sebentar sebelum mengirim ulang.');
        }
    }
    $user->update(['email_resend_at' => now()]);
    $user->sendEmailVerificationNotification();
    return redirect()->route('verification.notice')
        ->with('success', 'Link verifikasi sudah dikirim ulang. Cek inbox atau folder spam.');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ── Password reset
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');

Route::post('/forgot-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    $throttleKey = 'password-reset:' . $request->email;
    if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 1)) {
        $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
        return back()->withErrors(['email' => "Tunggu {$seconds} detik sebelum meminta link reset lagi."]);
    }
    \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 30);
    $status = \Illuminate\Support\Facades\Password::sendResetLink($request->only('email'));
    return $status === \Illuminate\Support\Facades\Password::RESET_LINK_SENT
        ? back()->with('success', 'Link reset password sudah dikirim ke email kamu.')
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', fn(string $token) => view('auth.reset-password', ['token' => $token]))->name('password.reset');

Route::post('/reset-password', function (\Illuminate\Http\Request $request) {
    $request->validate(['token' => 'required', 'email' => 'required|email', 'password' => 'required|min:8|confirmed']);
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill(['password' => Hash::make($password)])->setRememberToken(Str::random(60));
            $user->save();
        }
    );
    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.')
        : back()->withErrors(['email' => __($status)]);
})->name('password.update');

// ── Wilayah proxy
Route::get('/api/wilayah/{path}', function (string $path) {
    $cleanPath = str_replace('.json', '', $path);
    $segment   = explode('/', $cleanPath)[0];
    if (!in_array($segment, ['provinces', 'regencies', 'districts', 'villages'])) abort(404);
    $response = \Illuminate\Support\Facades\Http::timeout(15)->get("https://wilayah.id/api/{$cleanPath}.json");
    if (!$response->ok()) return response()->json(['data' => []]);
    return response($response->body())
        ->header('Content-Type', 'application/json')
        ->header('Cache-Control', 'public, max-age=86400');
})->where('path', '.*')->name('api.wilayah');

// ── Auth
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// ── Store register (public)
Route::get('/store/register', [StoreController::class, 'create'])->name('store.register');
Route::post('/store/register', [StoreController::class, 'store'])->name('store.register.post');
Route::get('/store/pending', [StoreController::class, 'pending'])->name('store.pending');
Route::get('/store/edit', [StoreController::class, 'edit'])->name('store.edit');
Route::post('/store/resubmit', [StoreController::class, 'resubmit'])->name('store.resubmit');
Route::post('/store/cancel', [StoreController::class, 'cancel'])->name('store.cancel');

// ── Cart (sebagian public)
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/select/{id}', [CartController::class, 'select'])->name('cart.select');
Route::post('/cart/select-all', [CartController::class, 'selectAll'])->name('cart.selectAll');
Route::post('/cart/remove-selected', [CartController::class, 'removeSelected'])->name('cart.removeSelected');
Route::post('/checkout/select', [CheckoutController::class, 'select'])->name('checkout.select');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// ── Protected (wajib login)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/addresses', [ProfileController::class, 'storeAddress'])->name('addresses.store');
    Route::post('/addresses/{address}/default', [ProfileController::class, 'setDefault'])->name('addresses.default');
    Route::delete('/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('addresses.destroy');

    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});


// ADMIN

Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export', [DashboardController::class, 'export'])->name('dashboard.export');

        // Products
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::delete('/products/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/toggle', [AdminProductController::class, 'toggleActive'])->name('products.toggle');

        // Merchants
        Route::get('/merchants', [MerchantController::class, 'index'])->name('merchants.index');
        Route::get('/merchants/{store}/export', [MerchantController::class, 'export'])->name('merchants.export');
        Route::get('/merchants/{store}', [MerchantController::class, 'show'])->name('merchants.show');

        // Stores
        Route::get('/stores', [App\Http\Controllers\Admin\StoreController::class, 'index'])->name('stores.index');
        Route::post('/stores/{store}/approve', [App\Http\Controllers\Admin\StoreController::class, 'approve'])->name('stores.approve');
        Route::post('/stores/{store}/reject', [App\Http\Controllers\Admin\StoreController::class, 'reject'])->name('stores.reject');
        Route::post('/stores/{store}/ban', [App\Http\Controllers\Admin\StoreController::class, 'ban'])->name('stores.ban');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        // Settings
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/avatar', [AdminSettingsController::class, 'updateAvatar'])->name('settings.avatar');
        Route::post('/settings/store', [AdminSettingsController::class, 'update'])->name('settings.store.update');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/categories/{category}/toggle', [CategoryController::class, 'toggle'])->name('categories.toggle');

        // ── Banners beranda
        Route::post('/banners/reorder', [BannerController::class, 'reorder'])->name('banners.reorder');
        Route::resource('banners', BannerController::class)->except(['show']);
        Route::post('/banners/{banner}/toggle', [BannerController::class, 'toggle'])->name('banners.toggle');

        // ── Home sections beranda
        Route::get('/home-sections', [HomeSectionController::class, 'index'])->name('home-sections.index');
        Route::post('/home-sections', [HomeSectionController::class, 'store'])->name('home-sections.store');
        Route::post('/home-sections/reorder', [HomeSectionController::class, 'reorder'])->name('home-sections.reorder');
        Route::put('/home-sections/{section}', [HomeSectionController::class, 'update'])->name('home-sections.update');
        Route::delete('/home-sections/{section}', [HomeSectionController::class, 'destroy'])->name('home-sections.destroy');
        Route::post('/home-sections/{section}/toggle', [HomeSectionController::class, 'toggle'])->name('home-sections.toggle');
        Route::post('/home-sections/{section}/products', [HomeSectionController::class, 'addProduct'])->name('home-sections.products.add');
        Route::delete('/home-sections/{section}/products/{product}', [HomeSectionController::class, 'removeProduct'])->name('home-sections.products.remove');

        // ── Store content (toko official)
        Route::get('/store-content', [StoreBannerController::class, 'index'])->name('store-content.index');

        Route::post('/store-banners/reorder', [StoreBannerController::class, 'reorder'])->name('store-banners.reorder');
        Route::post('/store-banners', [StoreBannerController::class, 'store'])->name('store-banners.store');
        Route::put('/store-banners/{banner}', [StoreBannerController::class, 'update'])->name('store-banners.update');
        Route::delete('/store-banners/{banner}', [StoreBannerController::class, 'destroy'])->name('store-banners.destroy');
        Route::post('/store-banners/{banner}/toggle', [StoreBannerController::class, 'toggle'])->name('store-banners.toggle');

        Route::post('/store-sections/reorder', [StoreSectionController::class, 'reorder'])->name('store-sections.reorder');
        Route::post('/store-sections', [StoreSectionController::class, 'store'])->name('store-sections.store');
        Route::put('/store-sections/{section}', [StoreSectionController::class, 'update'])->name('store-sections.update');
        Route::delete('/store-sections/{section}', [StoreSectionController::class, 'destroy'])->name('store-sections.destroy');
        Route::post('/store-sections/{section}/toggle', [StoreSectionController::class, 'toggle'])->name('store-sections.toggle');
        Route::post('/store-sections/{section}/products', [StoreSectionController::class, 'addProduct'])->name('store-sections.products.add');
        Route::delete('/store-sections/{section}/products/{product}', [StoreSectionController::class, 'removeProduct'])->name('store-sections.products.remove');

        // Traffic & Reports
        Route::get('/traffic', [TrafficController::class, 'index'])->name('traffic.index');
        Route::get('/traffic/export', [TrafficController::class, 'export'])->name('traffic.export');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

        // Notifications
        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/count', [DashboardController::class, 'notificationsCount'])->name('notifications.count');
        Route::post('/notifications/read-all', [AdminNotificationController::class, 'readAll'])->name('notifications.readAll');
        Route::get('/notifications/{notification}/read', [AdminNotificationController::class, 'read'])->name('notifications.read');
    });

// MERCHANT

// Daftar toko — wajib verifikasi email (di luar group isMerchant)
Route::middleware('auth')->group(function () {
    Route::get('/store/register', function () {
        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Verifikasi email kamu terlebih dahulu sebelum membuka toko.');
        }
        return app(\App\Http\Controllers\StoreController::class)->create(request());
    })->name('store.register');

    Route::post('/store/register', function (\Illuminate\Http\Request $request) {
        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Verifikasi email kamu terlebih dahulu sebelum membuka toko.');
        }
        return app(\App\Http\Controllers\StoreController::class)->store($request);
    })->name('store.register.post');

    Route::get('/store/pending', [\App\Http\Controllers\StoreController::class, 'pending'])->name('store.pending');
    Route::get('/store/edit', [\App\Http\Controllers\StoreController::class, 'edit'])->name('store.edit');
    Route::post('/store/resubmit', [\App\Http\Controllers\StoreController::class, 'resubmit'])->name('store.resubmit');
    Route::post('/store/cancel', [\App\Http\Controllers\StoreController::class, 'cancel'])->name('store.cancel');
});

Route::middleware(['auth', 'isMerchant'])
    ->prefix('merchant')
    ->name('merchant.')
    ->group(function () {

        Route::get('/dashboard', [MerchantDashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::get('/products', [MerchantProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [MerchantProductController::class, 'create'])->name('products.create');
        Route::post('/products', [MerchantProductController::class, 'store'])->name('products.store');
        Route::delete('/products/images/{image}', [MerchantProductController::class, 'destroyImage'])->name('products.images.destroy');
        Route::get('/products/{product}/edit', [MerchantProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [MerchantProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [MerchantProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/toggle', [MerchantProductController::class, 'toggleActive'])->name('products.toggle');

        // Categories (merchant punya sendiri)
        Route::get('/categories', [MerchantCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [MerchantCategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [MerchantCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [MerchantCategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/categories/{category}/toggle', [MerchantCategoryController::class, 'toggle'])->name('categories.toggle');

        // Orders
        Route::get('/orders/export', [MerchantOrderController::class, 'export'])->name('orders.export');
        Route::get('/orders', [MerchantOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [MerchantOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [MerchantOrderController::class, 'updateStatus'])->name('orders.status');

        // Settings
        Route::get('/settings', [MerchantSettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [MerchantSettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/logo', [MerchantSettingsController::class, 'updateLogo'])->name('settings.logo');

        // Store Appearance
        Route::get('/store-appearance', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'index'])->name('store.appearance');

        // ── Banners merchant
        Route::post('/banners/reorder', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'reorderBanner'])->name('banners.reorder');
        Route::post('/banners', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'storeBanner'])->name('banners.store');
        Route::put('/banners/{banner}', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'updateBanner'])->name('banners.update');
        Route::post('/banners/{banner}/toggle', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'toggleBanner'])->name('banners.toggle');
        Route::delete('/banners/{banner}', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'destroyBanner'])->name('banners.destroy');

        // ── Store Sections merchant
        Route::post('/store-sections', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'storeSection'])->name('store-sections.store');
        Route::put('/store-sections/{section}', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'updateSection'])->name('store-sections.update');
        Route::post('/store-sections/{section}/toggle', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'toggleSection'])->name('store-sections.toggle');
        Route::delete('/store-sections/{section}', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'destroySection'])->name('store-sections.destroy');
        Route::post('/store-sections/{section}/products', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'addProduct'])->name('store-sections.products.add');
        Route::delete('/store-sections/{section}/products/{product}', [App\Http\Controllers\Merchant\StoreAppearanceController::class, 'removeProduct'])->name('store-sections.products.remove');

        // Notifications
        Route::get('/notifications', [MerchantNotificationController::class, 'index'])->name('notifications');
        Route::get('/notifications/count', [MerchantNotificationController::class, 'count'])->name('notifications.count');
        Route::post('/notifications/read-all', [MerchantNotificationController::class, 'readAll'])->name('notifications.readAll');
        Route::get('/notifications/{notification}/read', [MerchantNotificationController::class, 'read'])->name('notifications.read');

        // Reports
        Route::get('/reports', [App\Http\Controllers\Merchant\ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [App\Http\Controllers\Merchant\ReportController::class, 'export'])->name('reports.export');
    });
