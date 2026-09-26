<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

try {
    $u = App\Models\User::first();
    $p = $u->getOrCreateProfile();
    echo 'Profile theme: ' . $p->theme . PHP_EOL;
    $s = $p->settings();
    echo 'Settings OK: ' . json_encode(array_keys($s)) . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
