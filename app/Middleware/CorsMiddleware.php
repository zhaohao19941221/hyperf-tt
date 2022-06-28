<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace App\Middleware;

use App\Constants\ContextConstant;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 跨域中间件
 * Class CorsMiddleware.
 */
class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);
        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            // Headers 可以根据实际情况进行改写。
            ->withHeader('Access-Control-Allow-Headers', 'DNT,Keep-Alive,User-Agent,Cache-Control,Content-Type,Authorization,X-Requested-With')
            ->withHeader('Cache-Control', 'no-store');
//            ->withHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');

        Context::set(ResponseInterface::class, $response);
        // 设置请求客户端ip地址
        Context::set(ContextConstant::HTTP_CLIENT_IP, $this->getClientIp($request));

        if ($request->getMethod() == 'OPTIONS') {
            return $response;
        }

        return $handler->handle($request);
    }

    /**
     * 获取客户端ip地址并记录上下文.
     * @return mixed|string
     */
    private function getClientIp(ServerRequestInterface $request)
    {
        $res = $request->getServerParams();
        if (isset($res['http_client_ip'])) {
            return $res['http_client_ip'];
        }

        if (isset($res['http_x_real_ip'])) {
            return $res['http_x_real_ip'];
        }

        if (isset($res['http_x_forwarded_for'])) {
            // 部分CDN会获取多层代理IP，所以转成数组取第一个值
            $arr = explode(',', $res['http_x_forwarded_for']);
            return $arr[0];
        }
        return $res['remote_addr'];
    }
}
