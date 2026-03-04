<?php
// Use Laravel's DB facade
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('carts')) {
    Schema::create('carts', function ($table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->unique();
        $table->string('session_id')->nullable();
        $table->timestamps();
    });
    echo "✅ Table carts créée\n";
} else {
    echo "ℹ️ Table existe déjà\n";
}
