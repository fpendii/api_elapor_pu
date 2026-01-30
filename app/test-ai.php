<?php
// Jalankan via terminal: php test-ai.php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$service = new \App\Services\GeminiService();
// Ganti dengan path foto yang ada di storage/app/public/reports/
$res = $service->analyzeDamage('reports/nama_foto_kamu.jpg');

print_r($res);
