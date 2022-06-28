<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
use App\Middleware\CorsMiddleware;
use Hyperf\HttpServer\Router\Router;

/*
 * 微信相关
 */
// 不需要登录验证的路由
Router::addGroup(env('ROUTE_PREFIX') . '/front/wechat', function () {
    // 测试专用
    Router::get('/test', 'WechatBundle\Controller\Front\V1\OfficialAccountController@test');
    Router::addGroup('/official_account', function () {
        // 微信响应
        Router::addRoute(['GET', 'POST', 'HEAD'], '/hello', 'WechatBundle\Controller\Front\V1\OfficialAccountController@hello');
        Router::addGroup('/material', function () {
            // 获取图片永久资源
            Router::get('/image', 'WechatBundle\Controller\Front\V1\OfficialAccountController@imageMaterial');
            // 上传图片永久资源
            Router::post('/upload_image', 'WechatBundle\Controller\Front\V1\OfficialAccountController@uploadImage');
            // 删除永久资源
            Router::delete('/image', 'WechatBundle\Controller\Front\V1\OfficialAccountController@deleteMaterial');
        });

        // 二维码
        Router::addGroup('/qrcode', function () {
            // 创建临时二维码
            Router::get('/temporary', 'WechatBundle\Controller\Front\V1\OfficialAccountController@temporaryQrcode');
            // 创建永久二维码
            Router::get('/forever', 'WechatBundle\Controller\Front\V1\OfficialAccountController@foreverQrcode');
            // 获取二维码图片base64
            Router::get('/url', 'WechatBundle\Controller\Front\V1\OfficialAccountController@getQrcodeUrl');
        });
        // 微信授权
        Router::addGroup('/oauth', function () {
            // 微信授权
            Router::get('/authorize', 'WechatBundle\Controller\Front\V1\OfficialAccountController@authorize');
            // 授权回调
            Router::get('/oauth_callback', 'WechatBundle\Controller\Front\V1\OfficialAccountController@oauthCallback');
        });
        // 菜单
        Router::addGroup('/menu', function () {
            // 查询
            Router::get('/list', 'WechatBundle\Controller\Front\V1\OfficialAccountController@menuList');
            // 设置默认菜单
            Router::post('/default', 'WechatBundle\Controller\Front\V1\OfficialAccountController@menuDefaultAdd');
            // 设置被邀请已注册的用户菜单
            Router::post('/promotion', 'WechatBundle\Controller\Front\V1\OfficialAccountController@menuPromotionAdd');
            // 设置未注册的被邀请用户菜单
            Router::post('/unregistered', 'WechatBundle\Controller\Front\V1\OfficialAccountController@menuUnregisteredPromotionAdd');
            // 删除菜单
            Router::delete('/delete', 'WechatBundle\Controller\Front\V1\OfficialAccountController@deleteMenu');
        });
        // 模板消息
        Router::addGroup('/template', function () {
            // 发送模板消息
            Router::post('/tpl', 'WechatBundle\Controller\Front\V1\OfficialAccountController@tpl');
            // 添加模板
            Router::post('/add', 'WechatBundle\Controller\Front\V1\OfficialAccountController@addTemplate');
            // 获取所有模板
            Router::get('/list', 'WechatBundle\Controller\Front\V1\OfficialAccountController@getTemplates');
            // 删除模板
            Router::delete('/delete', 'WechatBundle\Controller\Front\V1\OfficialAccountController@deleteTemplate');
        });
        // 标签
        Router::addGroup('/tag', function () {
            // 查询所有的用户标签
            Router::get('/list', 'WechatBundle\Controller\Front\V1\OfficialAccountController@taglist');
            // 添加用户标签
            Router::post('/add', 'WechatBundle\Controller\Front\V1\OfficialAccountController@addUserTag');
            // 删除用户标签
            Router::delete('/delete', 'WechatBundle\Controller\Front\V1\OfficialAccountController@deleteUserTag');
            // 批量给用户增加标签
            Router::post('/tag-users', 'WechatBundle\Controller\Front\V1\OfficialAccountController@setUserTagByOpenids');
            // 批量为用户移除标签
            Router::delete('/tag-users', 'WechatBundle\Controller\Front\V1\OfficialAccountController@deleteUserTagByOpenids');
            // 获取指定标签下的用户
            Router::get('/get-users-of-tag', 'WechatBundle\Controller\Front\V1\OfficialAccountController@getUsersOfTag');
            // 获取指定 openid 用户所属的标签
            Router::get('/user-tags', 'WechatBundle\Controller\Front\V1\OfficialAccountController@getuserTagsByOpenids');
        });
    });
}, ['middleware' => [CorsMiddleware::class]]);
