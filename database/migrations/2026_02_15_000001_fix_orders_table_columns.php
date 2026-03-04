<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Renommer total_amount en total s'il existe
            if (Schema::hasColumn('orders', 'total_amount') && !Schema::hasColumn('orders', 'total')) {
                $table->renameColumn('total_amount', 'total');
            }
            
            // S'assurer que les colonnes de contact existent
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('total');
            }
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }
            if (!Schema::hasColumn('orders', 'customer_address')) {
                $table->text('customer_address')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('orders', 'customer_city')) {
                $table->string('customer_city')->nullable()->after('customer_address');
            }
            if (!Schema::hasColumn('orders', 'customer_postal_code')) {
                $table->string('customer_postal_code')->nullable()->after('customer_city');
            }
            if (!Schema::hasColumn('orders', 'customer_country')) {
                $table->string('customer_country')->nullable()->after('customer_postal_code');
            }
            
            // S'assurer que order_number et tracking_number existent
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number')->unique()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('order_number');
            }
            
            // S'assurer que total a une valeur par défaut
            if (Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 12, 2)->default(0)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rollback
        });
    }
};
