<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = \App\Models\User::first(); // Grab first user to act as Auth
\Illuminate\Support\Facades\Auth::login($user);

$request = Illuminate\Http\Request::create('/portal/messages', 'GET');
$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . PHP_EOL;
echo $response->getContent();
