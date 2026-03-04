<?php
// Small script to bootstrap Laravel and check admin user existence.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = getenv('SAKHA_ADMIN_EMAIL') ?: 'dramealhassane7@gmail.com';

$u = \App\Models\User::where('email', $email)->first();

if ($u) {
    echo 'FOUND: ' . $u->id . ' ' . $u->email . PHP_EOL;
    exit(0);
}

echo 'NOT FOUND' . PHP_EOL;
exit(1);
