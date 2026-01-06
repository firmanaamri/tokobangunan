<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

// Get the user
$user = \App\Models\User::where('email', 'admin@tokobangunan.com')->first();

if (!$user) {
    echo "User not found\n";
    exit(1);
}

echo "Found user: " . $user->email . "\n";
echo "Password from DB (hashed): " . substr($user->password, 0, 30) . "...\n";

// Check password
$isCorrect = \Illuminate\Support\Facades\Hash::check('admin123', $user->password);

echo "Password 'admin123' check: " . ($isCorrect ? "CORRECT" : "INCORRECT") . "\n";

// Try login
$auth = \Illuminate\Support\Facades\Auth::attempt([
    'email' => 'admin@tokobangunan.com',
    'password' => 'admin123'
]);

echo "Login attempt: " . ($auth ? "SUCCESS" : "FAILED") . "\n";

if (\Illuminate\Support\Facades\Auth::check()) {
    echo "Authenticated user: " . \Illuminate\Support\Facades\Auth::user()->email . "\n";
}
