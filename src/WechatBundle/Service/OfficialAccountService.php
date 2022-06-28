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
use App\Exception\ResponseException;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Transfer;
use Hyperf\Di\Annotation\Inject;
use UserBundle\Constants\UserWechatStatusConstant;
use UserBundle\Service\UserService;
use UserBundle\Service\UserWechatService;
use WechatBundle\Kernel\WechatKernel;

class OfficialAccountService
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
     * @Inject
     * @var UserWechatService
     */
    private $userWechatService;

    /**
     * @Inject
     * @var UserService
     */
    private $userService;

    /**
     * OfficialAccountService constructor.
     */
    public function __construct()
    {
        $this->config = config('wechat');
    }

    /**
     * 网页授权.
     * @return string
     */
    public function authorize(array $param)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        $paramurl = http_build_query($param, '', '&');
        $url = config('common.api_domain') . $this->config['oauth']['callback'] . '?' . $paramurl;
//        $redirectUrl = urlencode($url);
        return $app->oauth->redirect($url);
    }

    /**
     * 获取微信永久素材.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function getPermanentMaterial(string $type, int $offset, int $count)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->material->list($type, $offset, $count);
    }

    /**
     * 删除永久素材.
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function deleteImageMaterial(string $mediaId)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->material->delete($mediaId);
    }

    /**
     * 上传图片永久资源.
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function uploadImageMaterial(string $path)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->material->uploadImage($path);
    }

    /**
     * 报价申请结果模板消息.
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendTemplateQuotationResults(string $openid, string $url, array $data)
    {
        if (! isset($data['plate_number']) || ! isset($data['result'])) {
            throw new ResponseException(ErrorCode::USER_VEHICLE_PARAMS_ERROR);
        }
        $tplData = [
            'number' => $data['plate_number'],
            // 审核失败原因
            'result' => $data['result'],
            'note' => isset($data['note']) ? $data['note'] : '点击详情',
        ];
        return $this->sendTemplateMessage('quotation_results', $openid, $url, $tplData);
    }

    /**
     * 报价成功通知模板消息.
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendTemplateQuotationSuccess(string $openid, string $url, array $data)
    {
        if (! isset($data['plate_number']) || ! isset($data['created_at']) || ! isset($data['help_estimate_price'])) {
            throw new ResponseException(ErrorCode::USER_VEHICLE_PARAMS_ERROR);
        }
        $tplData = [
            'number' => $data['plate_number'],
            'money' => $data['help_estimate_price'],
            //            'money' => ['value' => $data['help_estimate_price'], 'color' => '#F00F00'],
            'time' => $data['created_at'],
            'note' => isset($data['note']) ? $data['note'] : '点击详情',
        ];
        return $this->sendTemplateMessage('quotation_success', $openid, $url, $tplData);
    }

    /**
     * 发送模板消息.
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function sendTemplateMessage(string $templateType, string $openid, string $url, array $data)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        $result = $app->template_message->send([
            'touser' => $openid,
            'template_id' => $this->config['wechat_tpl'][$templateType],
            'url' => $url,
            //            'miniprogram' => [
            //                'appid' => 'xxxxxxx',
            //                'pagepath' => 'pages/xxx',
            //            ],
            'data' => $data,
            //                [
            //                    'key1' => 'VALUE',
            //                    'key2' => 'VALUE2',
            //                ],
        ]);
        if (! $result['errcode']) {// 发送成功
//            logger('wechat_tpl_send')->info(sprintf('[%s:%s],data:%s,result:%s', $templateType, $openid, $data, json_encode($result)));
        } else {
            // 发送失败记录日志
            logger('wechat_tpl_fail')->error(sprintf('[%s:%s],data:%s,result:%s', $templateType, $openid, $data, json_encode($result)));
        }
        return $result;
    }

    /**
     * 为新注册的用户添加用户标签.
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return bool
     */
    public function setRegisterUserTag(int $uid)
    {
        $wxUser = $this->userService->getWxUserInfoByUid($uid);
        if (empty($wxUser) || empty($wxUser['ext'])) {
            // 没有微信绑定信息
            return false;
        }
        $openid = $wxUser['ext']['openid'];
        $userWechat = $this->userWechatService->getUserByOpenid($openid);
        if (empty($userWechat) || $userWechat['status'] == UserWechatStatusConstant::USER_STATUS_UNSUBSCRIBE) {
            // 没有关注公众号
            return false;
        }
        // 删除已邀请未注册的标签
        $this->deleteUserTagByOpenids([$openid], $this->config['menu_match_rule_tag']['user_invited_unregistered']);
        // 添加已邀请已注册的标签
        $this->setUserTagByOpenids([$openid], $this->config['menu_match_rule_tag']['user_invited_by_promotion']);
        return true;
    }

    /**
     * 给微信用户设置用户标签.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function setUserTagByOpenids(array $openids, int $tagId)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->user_tag->tagUsers($openids, $tagId);
    }

    /**
     * 批量为用户删除标签.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function deleteUserTagByOpenids(array $openids, int $tagId)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->user_tag->untagUsers($openids, $tagId);
    }

    /**
     * 获取用户下的tag.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function usersOfTag(int $tagId)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->user_tag->usersOfTag($tagId, $nextOpenId = '');
    }

    /**
     * 获取指定 openid 用户所属的标签.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function getuserTagsByOpenids(string $openid)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $userTags = $app->user_tag->userTags($openid);
    }

    /**
     * 设置微信公众号菜单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return mixed
     */
    public function createMenu(array $buttons, array $matchRule = [])
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        if (empty($matchRule)) {
            return $app->menu->create($buttons);
        }
        $tagId = $this->config['menu_match_rule_tag'][$matchRule['tag_type']];
        $rule = ['tag_id' => $tagId];
        return $app->menu->create($buttons, $rule);
    }

    /**
     * 删除菜单.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return mixed
     */
    public function deleteMenu(int $menuId)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        if (empty($menuId)) {
            return $app->menu->delete();
        }
        return $app->menu->delete($menuId);
    }

    /**
     * 查询已设置菜单.
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return mixed
     */
    public function menuList()
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->menu->list();
    }

    /**
     * 添加模板消息的模板
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function addTemplate(string $shortId)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->template_message->addTemplate($shortId);
    }

    /**
     * 获取所有模板列表.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function getTemplates()
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->template_message->getPrivateTemplates();
    }

    /**
     * 删除模板消息的模板
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function deleteTemplate(string $templateId)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->template_message->deletePrivateTemplate($templateId);
    }

    /**
     * 获取所有用户标签.
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function getUserTagList()
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->user_tag->list();
    }

    /**
     * 添加用户标签.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function addUserTag(string $name)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->user_tag->create($name);
    }

    /**
     * 删除用户标签.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function deleteUserTag(int $tagId)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->user_tag->delete($tagId);
    }

    /**
     * 响应微信
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @return false|string|\Symfony\Component\HttpFoundation\Response
     */
    public function hello()
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        $app->server->push(function ($message) use ($app) {
            switch ($message['MsgType']) {
                case 'event':
                    $user_openid = $message['FromUserName'];
                    $message['user'] = $app->user->get($user_openid);
                    return $this->event($message, $app); // 处理点击事件
                case 'text':
                    return $this->replyText($message['Content']);
                default:
                    // 转发收到的消息给客服
                    return new Transfer();
            }
        });
        $response = $app->server->serve();
        return $response->getContent();
    }

    public function replyText(string $content)
    {
        // 转发收到的消息给客服
        return new Transfer();
    }

    public function test($userOpenid)
    {
        return $userWechat = $this->userWechatService->getUserByOpenid($userOpenid);
        return $this->userWechatService->updateOrCreateByOpenid($userOpenid, ['status' => UserWechatStatusConstant::USER_STATUS_SUBSCRIBE, 'subscribe_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * 处理公众号点击事件.
     * @param $eventKey
     * @return Image|string
     */
    public function clickEvent($eventKey)
    {
        switch ($eventKey) {
            case 'DEFAULT_MENU_BRAND_INTRODUCTION':
                // 品牌简介
                return new Image(config('wechat.menu_img.intro'));
            default:
                return '';
        }
    }

    /**
     * 创建临时二维码
     * @param $sceneValue
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function temporaryQrcode($sceneValue)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->qrcode->temporary($sceneValue, $this->config['qrcode_expire_seconds']);
    }

    /**
     * 创建永久二维码
     * @param $sceneValue
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function foreverQrcode($sceneValue)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        return $app->qrcode->forever($sceneValue);
    }

    /**
     * 获取二维码的url和图片base64.
     * @param $ticket
     * @return string
     */
    public function getQrcodeUrl($ticket)
    {
        $app = $this->wechatKernel->officialAccount($this->config);
        $url = $app->qrcode->url($ticket);
        $content = file_get_contents($url); // 得到二进制图片内容
        return base64_encode($content);
    }
}
