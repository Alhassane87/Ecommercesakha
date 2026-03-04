<?php
// Script to set admin password from .env into the user record.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = getenv('SAKHA_ADMIN_EMAIL') ?: 'dramealhassane7@gmail.com';
$pwd = getenv('SAKHA_ADMIN_PASSWORD') ?: null;

if (!$pwd) {
    echo "SAKHA_ADMIN_PASSWORD not set in environment.\n";
    exit(2);
}

$u = \App\Models\User::where('email', $email)->first();
if (!$u) {
    echo "ADMIN USER NOT FOUND\n";
    exit(1);
}

// Use Laravel's Hash facade if available, otherwise PHP password_hash
if (class_exists('Illuminate\\Support\\Facades\\Hash')) {
    $u->password = \Illuminate\Support\Facades\Hash::make($pwd);
} else {
    $u->password = password_hash($pwd, PASSWORD_BCRYPT);
}
$u->save();

echo "UPDATED PASSWORD FOR: " . $u->id . " " . $u->email . PHP_EOL;
exit(0);
