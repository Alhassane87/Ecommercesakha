<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'public_token_expires_at')) {
                $table->timestamp('public_token_expires_at')->nullable()->after('public_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'public_token_expires_at')) {
                $table->dropColumn('public_token_expires_at');
            }
        });
    }
};
