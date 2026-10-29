<?php
// Vercel Functions are read-only except /tmp. No durable data is stored here.
foreach (['/tmp/laravel-views','/tmp/laravel-storage','/tmp/laravel-storage/framework','/tmp/laravel-storage/framework/cache','/tmp/laravel-storage/framework/sessions','/tmp/laravel-storage/framework/views','/tmp/laravel-storage/logs'] as $dir) {
    if (!is_dir($dir)) { mkdir($dir, 0700, true); }
}
putenv('VIEW_COMPILED_PATH=/tmp/laravel-views');
$_ENV['VIEW_COMPILED_PATH']='/tmp/laravel-views';
require __DIR__.'/../public/index.php';
