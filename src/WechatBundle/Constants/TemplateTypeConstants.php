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
 * 微信模板消息类型枚举类
 * Class TemplateTypeConstants.
 * @Constants
 */
class TemplateTypeConstants extends AbstractConstants
{
    /**
     * @Message("充值成功通知")
     */
    public const TEMPLATE_TYPE_PAY_COST_SUCCESS = 'pay_cost_success';
}
