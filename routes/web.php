<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Http\Controllers\Admin\AdCampaignController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\ChatbotController as AdminChatbotController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\GoogleController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

use App\Models\Category;
use App\Models\Product;

Route::get('/', [ProductController::class, 'home'])->name('home');

// ==================== NOUVELLES ROUTES PUBLIQUES POUR CATÉGORIES ====================
Route::get('/categories', [AdminCategoryController::class, 'publicIndex'])->name('categories.index');
Route::get('/categories/{slug}', [AdminCategoryController::class, 'publicShow'])->name('categories.show');

Route::get('/dashboard', function () {
    // If the authenticated user is an admin, send them to the admin dashboard.
    $user = auth()->user();
    if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes (requires role=admin)
Route::middleware(['auth', EnsureUserIsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // simple product management routes could be added here
    Route::resource('products', AdminProductController::class)->names('admin.products');
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])->name('admin.products.images.destroy');
    Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
    Route::resource('users', AdminUserController::class)->except(['show'])->names('admin.users');
    // Admin order management
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)
        ->only(['index','edit','update','destroy'])
        ->names('admin.orders');
    
    // Gestion des attributs de catégories
    Route::post('categories/{category}/attributes', [AdminCategoryController::class, 'storeAttribute'])->name('admin.categories.attributes.store');
    Route::put('categories/attributes/{attribute}', [AdminCategoryController::class, 'updateAttribute'])->name('admin.categories.attributes.update');
    Route::delete('categories/attributes/{attribute}', [AdminCategoryController::class, 'destroyAttribute'])->name('admin.categories.attributes.destroy');
    Route::post('categories/attributes/{attribute}/values', [AdminCategoryController::class, 'storeAttributeValue'])->name('admin.categories.attributes.values.store');
    Route::delete('categories/attributes/values/{value}', [AdminCategoryController::class, 'destroyAttributeValue'])->name('admin.categories.attributes.values.destroy');
    
    // API endpoint to get category attributes (for product creation form)
    Route::get('categories/{category}/attributes-api', [AdminCategoryController::class, 'getAttributesJson'])->name('admin.categories.attributes-api');
    
    // Gestion des variations de produits
    Route::post('products/{product}/variations', [AdminProductController::class, 'storeVariation'])->name('admin.products.variations.store');
    Route::put('products/variations/{variation}', [AdminProductController::class, 'updateVariation'])->name('admin.products.variations.update');
    Route::delete('products/variations/{variation}', [AdminProductController::class, 'destroyVariation'])->name('admin.products.variations.destroy');
    
    // Gestion des promotions
    Route::resource('promotions', AdminPromotionController::class)->names('admin.promotions');
    Route::delete('ad-campaigns/{adCampaign}/images/{image}', [AdCampaignController::class, 'destroyImage'])
        ->name('admin.ad-campaigns.images.destroy');
    Route::post('ad-campaigns/{adCampaign}/images/{image}/primary', [AdCampaignController::class, 'setPrimaryImage'])
        ->name('admin.ad-campaigns.images.primary');
    Route::resource('ad-campaigns', AdCampaignController::class)
        ->except(['show'])
        ->parameters(['ad-campaigns' => 'adCampaign'])
        ->names('admin.ad-campaigns');
    
    Route::get('chatbot', [AdminChatbotController::class, 'index'])->name('admin.chatbot.index');
    Route::get('chatbot/stats', [AdminChatbotController::class, 'stats'])->name('admin.chatbot.stats');
    Route::get('chatbot/{id}', [AdminChatbotController::class, 'show'])->name('admin.chatbot.show');
    Route::post('chatbot/whatsapp', [AdminChatbotController::class, 'sendWhatsApp'])->name('admin.chatbot.whatsapp');
});

require __DIR__.'/auth.php';

Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Socialite routes (redirect + callback)
Route::get('/auth/redirect/{provider}', [\App\Http\Controllers\Auth\SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/callback/{provider}', [\App\Http\Controllers\Auth\SocialAuthController::class, 'callback'])->name('social.callback');

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Cart and checkout should be usable by guests (session-based carts are supported).
// Cart routes - GET et POST sur la même route /cart
Route::match(['GET', 'POST'], '/cart', [CartController::class, 'index'])->name('cart.index');
// Backward-compatible endpoint used by older frontend scripts.
Route::post('/cart/add', [CartController::class, 'index'])->name('cart.add');
Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

// Orders list requires auth (user dashboard), but individual order pages can be viewed
// by owner (auth), admin, or via a public token (tracking number). We expose show
// publicly and handle authorization inside the controller.
Route::middleware('auth')->get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

// Public tracking form
Route::get('/track', [\App\Http\Controllers\TrackController::class, 'form'])->name('track.form');
Route::post('/track', [\App\Http\Controllers\TrackController::class, 'lookup'])->name('track.lookup');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::view('/livraison', 'pages.shipping')->name('info.shipping');
Route::view('/retours', 'pages.returns')->name('info.returns');
Route::view('/faq', 'pages.faq')->name('info.faq');
Route::view('/mentions-legales', 'pages.legal-notice')->name('legal.notice');
Route::view('/politique-confidentialite', 'pages.privacy')->name('legal.privacy');
Route::view('/cgv', 'pages.terms')->name('legal.terms');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:10,1')
    ->name('newsletter.subscribe');
Route::get('/ads/click/{adCampaign}', [AdController::class, 'click'])->name('ads.click');

// Webhooks
Route::post('/webhooks/stripe', [WebhookController::class, 'stripe']);
Route::get('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'verify']);
Route::post('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
Route::post('/webhooks/{provider}', [WebhookController::class, 'generic']);

Route::post('/chatbot/message', [ChatbotController::class, 'sendMessage'])->name('chatbot.message');
Route::get('/chatbot/history', [ChatbotController::class, 'getHistory'])->name('chatbot.history');
