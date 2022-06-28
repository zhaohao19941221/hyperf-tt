<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace App\Exception;

use Hyperf\Server\Exception\ServerException;
use Throwable;

class ServiceException extends ServerException
{
    public function __construct(string $message = null, int $code = 0, Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = 'test';
        }
        parent::__construct($message, $code, $previous);
    }
}
