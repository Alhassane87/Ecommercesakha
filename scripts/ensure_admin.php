<?php
// Ensure admin user has role 'admin' and optionally update password from .env
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = getenv('SAKHA_ADMIN_EMAIL') ?: 'dramealhassane7@gmail.com';
$pwd = getenv('SAKHA_ADMIN_PASSWORD') ?: null;

$user = \App\Models\User::where('email', $email)->first();
if (! $user) {
    echo "ADMIN USER NOT FOUND for email: $email" . PHP_EOL;
    exit(1);
}

$changed = false;
if ($user->role !== 'admin') {
    $user->role = 'admin';
    $changed = true;
}

if ($pwd) {
    if (class_exists('Illuminate\\Support\\Facades\\Hash')) {
        $user->password = \Illuminate\Support\Facades\Hash::make($pwd);
    } else {
        $user->password = password_hash($pwd, PASSWORD_BCRYPT);
    }
    $changed = true;
}

if ($changed) {
    $user->save();
    echo "UPDATED ADMIN: {$user->id} {$user->email} (role={$user->role})" . PHP_EOL;
} else {
    echo "NO CHANGES NEEDED for admin: {$user->id} {$user->email} (role={$user->role})" . PHP_EOL;
}

exit(0);
