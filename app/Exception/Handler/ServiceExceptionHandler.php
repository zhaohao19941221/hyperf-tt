<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace App\Exception\Handler;

use App\Controller\Response;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ServiceExceptionHandler extends ExceptionHandler
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    /**
     * @var Response
     */
    protected $response;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->response = $container->get(Response::class);
        $this->logger = $container->get(StdoutLoggerInterface::class);
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 停止将异常传递到下一个
        $this->stopPropagation();
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
//        $this->logger->error($throwable->getTraceAsString());
        return $this->response->failed($throwable->getMessage(), $throwable->getCode());
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
