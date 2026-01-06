<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new \Symfony\Component\Console\Input\ArrayInput([
        'command' => 'tinker',
    ]),
    new \Symfony\Component\Console\Output\NullOutput
);

$user = \App\Models\User::where('email', 'admin@tokobangunan.com')->first();
echo "User: " . $user->email . "\n";
echo "Password from DB: " . $user->password . "\n";

$check = \Illuminate\Support\Facades\Hash::check('admin123', $user->password);
echo "Password check: " . ($check ? "PASS" : "FAIL") . "\n";

// Try auth
$attempt = \Illuminate\Support\Facades\Auth::attempt([
    'email' => 'admin@tokobangunan.com',
    'password' => 'admin123'
]);

echo "Auth attempt: " . ($attempt ? "SUCCESS" : "FAILED") . "\n";
