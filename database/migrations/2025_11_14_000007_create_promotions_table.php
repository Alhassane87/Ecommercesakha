<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage'); // Pourcentage ou montant fixe
            $table->decimal('value', 10, 2); // 30 pour 30% ou 5000 pour 5000 fcfa
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete(); // Promotion sur une catégorie
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete(); // Promotion sur un produit spécifique
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('min_quantity')->nullable(); // Quantité minimum pour appliquer la promo
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};

