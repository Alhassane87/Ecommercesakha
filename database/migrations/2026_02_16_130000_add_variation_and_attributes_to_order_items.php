<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_variation_id')) {
                $table->foreignId('product_variation_id')
                    ->nullable()
                    ->constrained('product_variations')
                    ->nullOnDelete()
                    ->after('product_id');
            }

            if (!Schema::hasColumn('order_items', 'selected_attributes')) {
                $table->json('selected_attributes')
                    ->nullable()
                    ->after('product_variation_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'selected_attributes')) {
                $table->dropColumn('selected_attributes');
            }

            if (Schema::hasColumn('order_items', 'product_variation_id')) {
                $table->dropForeign(['product_variation_id']);
                $table->dropColumn('product_variation_id');
            }
        });
    }
};

