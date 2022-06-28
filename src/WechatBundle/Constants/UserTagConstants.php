<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace WechatBundle\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * 微信用户标签枚举类
 * Class OnepluseUserStatusConstant.
 * @Constants
 */
class UserTagConstants extends AbstractConstants
{
    /**
     * @Message("注册用户")
     */
    public const WECHAT_USER_TAG_REGISTERED = 1;

    /**
     * @Message("代理")
     */
    public const WECHAT_USER_TAG_PROMOTER = 2;
}
