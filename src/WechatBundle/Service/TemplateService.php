<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace WechatBundle\Service;

use App\Constants\ErrorCode;
use Hyperf\Di\Annotation\Inject;
use UserBundle\Service\UserService;
use WechatBundle\Kernel\WechatKernel;

class TemplateService
{
    /**
     * @var mixed
     */
    private $config;

    /**
     * @Inject
     * @var WechatKernel
     */
    private $wechatKernel;

    /**
     * OfficialAccountService constructor.
     */
    public function __construct()
    {
        $this->config = config('wechat_template');
    }

    /**
     * 发送模板消息.
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function sendTemplateMessage(int $uid, array $data)
    {
        $xwUser = make(UserService::class)->getWxUserInfoByUid($uid);
        if (! empty($xwUser) && ! empty($xwUser['ext'])) {
            $openid = $xwUser['ext']['openid'];
        } else {
            // 发送失败记录日志
            logger('WECHAT SEND TEMPLATE MESSAGE FAIL OPENID NOT FIND')->error(sprintf('[%s:%s],data:%s,result:%s', $data['title'], $uid, $data, ErrorCode::getMessage(ErrorCode::WECHAT_OPENID_NOT_FIND_TEMPLATE_FAIL)));
            return ['errcode' => 0, 'errmsg' => ErrorCode::WECHAT_OPENID_NOT_FIND_TEMPLATE_FAIL];
        }
        $app = $this->wechatKernel->officialAccount(config('wechat'));
        $result = $app->template_message->send([
            'touser' => $openid,
            'template_id' => $data['template_id'],
            'url' => $data['url'] ?? '',
            'data' => $data['data'],
        ]);
        if ($result['errcode']) {
            // 发送失败记录日志
            logger('WECHAT SEND TEMPLATE MESSAGE FAIL')->error(sprintf('[%s:%s],data:%s,result:%s', $data['title'], $openid, $data, json_encode($result)));
        }
        return $result;
    }
}
