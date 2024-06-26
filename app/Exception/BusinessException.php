<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 *
 *
 */

namespace App\Exception;

use Hyperf\Server\Exception\ServerException;
use ProductBundle\Constants\DemoConstant;
use Throwable;

class BusinessException extends ServerException
{
    public function __construct(int $code = 0, ?string $message = null, ?Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = DemoConstant::getMessage($code);
        }

        parent::__construct($message, $code, $previous);
    }
}
