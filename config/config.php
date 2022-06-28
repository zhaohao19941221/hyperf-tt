<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Finder\Finder;

$env = env('APP_ENV', 'indev');
if ($env === 'indev') {
    $configPath = [BASE_PATH . '/config/indev'];
} elseif ($env === 'dev') {
    $configPath = [BASE_PATH . '/config/dev'];
} else {
    $configPath = [BASE_PATH . '/config/production'];
}

$finder = new Finder();
$finder->files()->in($configPath)->name('*.php');
$configs = [];
foreach ($finder as $file) {
    $configs[$file->getBasename('.php')] = require $file->getRealPath();
}
return array_merge_recursive([
    'app_name' => env('APP_NAME', 'skeleton'),
    'app_env' => $env,
    'scan_cacheable' => env('SCAN_CACHEABLE', false),
    StdoutLoggerInterface::class => [
        'log_level' => [
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::DEBUG,
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
        ],
    ],
], $configs);
