<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
return [
    'handler' => [
        'http' => [
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            Hyperf\Validation\ValidationExceptionHandler::class,
            App\Exception\Handler\AppExceptionHandler::class,
            App\Exception\Handler\FrontExceptionHandle::class,
            App\Exception\Handler\ServiceExceptionHandler::class,
        ],
    ],
];
