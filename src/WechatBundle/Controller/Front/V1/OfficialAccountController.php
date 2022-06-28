<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace WechatBundle\Controller\Front\V1;

use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use WechatBundle\Service\OfficialAccountService;

/**
 * 微信公众号接口
 * Class OfficialAccountController.
 */
class OfficialAccountController extends AbstractController
{
    /**
     * @Inject
     * @var OfficialAccountService
     */
    public $officialAccountService;

    /**
     * 响应微信
     * @return false|string
     */
    public function hello()
    {
        return $this->officialAccountService->hello();
    }
}
