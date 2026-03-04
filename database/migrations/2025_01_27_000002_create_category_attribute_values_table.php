<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_attribute_id')->constrained()->cascadeOnDelete();
            $table->string('value'); // Ex: "S", "M", "L", "XL" pour taille ou "Rouge", "Bleu" pour couleur
            $table->string('display_value')->nullable(); // Ex: "Petit (S)" pour affichage
            $table->string('color_code')->nullable(); // Pour les couleurs, code hex (#FF0000)
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_attribute_values');
    }
};

