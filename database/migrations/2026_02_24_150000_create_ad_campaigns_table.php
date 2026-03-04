<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('placement')->index();
            $table->string('audience')->default('all')->index(); // all, guests, authenticated
            $table->string('target_url')->nullable();
            $table->string('button_text', 80)->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('background_color', 7)->default('#0f172a');
            $table->string('text_color', 7)->default('#ffffff');
            $table->boolean('open_in_new_tab')->default(false);
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedInteger('priority')->default(100)->index();
            $table->unsignedSmallInteger('max_impressions_per_session')->nullable();
            $table->dateTime('starts_at')->nullable()->index();
            $table->dateTime('ends_at')->nullable()->index();
            $table->unsignedBigInteger('impressions')->default(0);
            $table->unsignedBigInteger('clicks')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_campaigns');
    }
};
