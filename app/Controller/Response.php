<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace App\Controller;

use App\Constants\ErrorCode;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Response.
 */
class Response
{
    protected ContainerInterface $container;

    /**
     * @var ResponseInterface
     */
    protected mixed $response;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->response = $container->get(ResponseInterface::class);
    }

    /**
     * @param $data
     */
    public function json($data): PsrResponseInterface
    {
        return $this->response->json($data);
    }

    public function success(array $data = [], string $message = 'success'): PsrResponseInterface
    {
        $data = [
            'code' => 0,
            'status' => 'success',
            'data' => $data,
        ];

        return $this->response->json($data);
    }

    public function error(int $code = ErrorCode::SERVER_ERROR, string $message = '', array $data = []): PsrResponseInterface
    {
        $errorCode = $code;
        if ($code > 4000) {
            $code = 422;
        }

        $data = [
            'code' => $code,
            'status' => $message,
            'data' => $data ?? [],
            'error' => [
                'message' => $message,
                'code' => $data['code'] ?? $errorCode,
                'status_code' => $code,
            ],
        ];

        if (in_array($code, [401, 405, 500, 422], true)) {
            return $this->response->withStatus($code)->json($data);
        }

        return $this->response->json($data);
    }

    public function response(): \Hyperf\HttpMessage\Server\Response
    {
        return Context::get(PsrResponseInterface::class);
    }
}
