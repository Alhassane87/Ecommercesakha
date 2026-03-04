<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('categories')->onDelete('cascade');
            }
            if (!Schema::hasColumn('categories', 'icon')) {
                $table->string('icon')->nullable()->after('slug');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['parent_id', 'icon']);
        });
    }
};
