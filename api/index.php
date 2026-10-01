<?php

// Prepare temporary storage directories for Vercel Serverless Environment
$storageDirs = [
    '/tmp/views',
    '/tmp/framework/sessions',
    '/tmp/framework/views',
    '/tmp/framework/cache',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Forward request to Laravel's public entrypoint
require __DIR__ . '/../public/index.php';
