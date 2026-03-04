<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->nullable()->unique();
            $table->json('attributes'); // {"taille": "M", "couleur": "Rouge"}
            $table->decimal('price', 10, 2)->nullable(); // Prix spécifique pour cette variation (si null, utilise le prix du produit)
            $table->integer('stock')->default(0);
            $table->string('image_path')->nullable(); // Image spécifique pour cette variation
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};

