<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace App\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * 公共上下文枚举类.
 * @Constants
 */
class ContextConstant extends AbstractConstants
{
    /**
     * @Message("HTTP客户端请求地址")
     */
    public const HTTP_CLIENT_IP = 'HTTP_CLIENT_IP';
}
