<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('stores a usage video when creating a product from admin', function () {
    Storage::fake('public');

    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.products.store'), [
        'name' => 'Creme de soin',
        'slug' => 'creme-de-soin',
        'price' => 12000,
        'stock' => 8,
        'usage_video' => UploadedFile::fake()->create('demo.mp4', 2048, 'video/mp4'),
    ]);

    $product = Product::query()->latest('id')->firstOrFail();

    $response->assertRedirect(route('admin.products.index'));

    expect($product->usage_video_path)->not->toBeNull();
    Storage::disk('public')->assertExists($product->usage_video_path);
});

it('shows the usage video on the public product page', function () {
    $product = Product::create([
        'name' => 'Lotion cheveux',
        'slug' => 'lotion-cheveux',
        'description' => 'Une lotion nourrissante.',
        'usage_video_path' => 'product_videos/tutorial.webm',
        'price' => 5000,
        'stock' => 4,
        'is_active' => true,
    ]);

    $response = $this->get(route('product.show', $product->slug));

    $response->assertOk()
        ->assertSee('Comment utiliser ce produit')
        ->assertSee('product_videos/tutorial.webm');
});
