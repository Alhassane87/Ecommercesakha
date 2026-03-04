<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ajouter la colonne uniquement si elle n'existe pas déjà pour éviter l'erreur 1060
        if (! Schema::hasColumn('products', 'is_active')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('slug');
            });
        }

        if (! Schema::hasColumn('categories', 'is_active')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('slug');
            });
        }
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};