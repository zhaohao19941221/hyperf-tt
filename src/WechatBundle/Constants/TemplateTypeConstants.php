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

    /**
     * @Message("计划审核通过")
     */
    public const TEMPLATE_TYPE_PLAN_REVIEW_SUCCESS = 'plan_review_success';

    /**
     * @Message("计划生效通知")
     */
    public const TEMPLATE_TYPE_PLAN_TAKE_EFFECT = 'plan_take_effect';

    /**
     * @Message("报价成功通知")
     */
    public const TEMPLATE_TYPE_QUOTATION_SUCCESS = 'quotation_success';

    /**
     * @Message("报价申请结果")
     */
    public const TEMPLATE_TYPE_QUOTATION_RESULT = 'quotation_results';
}
