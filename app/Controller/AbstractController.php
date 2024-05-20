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

namespace App\Controller;

use App\Constants\ErrorCode;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected RequestInterface $request;

    #[Inject]
    protected ResponseInterface $response;

    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/20 上午9:48
     * @Desc:成功的响应
     */
    protected function successResponse(array $data = [], array $cookieArr = []): \Psr\Http\Message\ResponseInterface
    {
        return $this->jsonResponse(0, 'success', $data, $cookieArr);
    }

    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/20 上午9:48
     * @Desc:错误的响应
     * @param mixed $code
     */
    protected function errorResponse($code, string $msg = '', array $data = [], array $cookieArr = []): \Psr\Http\Message\ResponseInterface
    {
        if (empty($msg)) {
            $msg = ErrorCode::getMessage($code);
        }
        return $this->jsonResponse($code, $msg, $data, $cookieArr);
    }

    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/20 上午9:48
     * @Desc:json响应
     */
    protected function jsonResponse(int $code = 0, string $message = 'success', array $data = [], array $cookieArr = [], array $headerArr = []): \Psr\Http\Message\ResponseInterface
    {
        $response = $this->response;
        if (! empty($cookieArr)) {
            foreach ($cookieArr as $cookie) {
                if ($cookie instanceof Cookie) {
                    $response = $response->withCookie($cookie);
                }
            }
        }
        if (! empty($headerArr)) {
            foreach ($headerArr as $key => $val) {
                $response = $response->withHeader($key, $val);
            }
        }
        return $response->json(['code' => $code, 'msg' => $message, 'data' => $data]);
    }
}
