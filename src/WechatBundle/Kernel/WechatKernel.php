<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace WechatBundle\Kernel;

use EasyWeChat\Factory;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;

class WechatKernel
{
    public const OFFICIAL_ACCOUNT = 'officialAccount';

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function make($method, $parameters)
    {
        return $this->{$method}($parameters);
    }

    /**
     * 微信公众号配置.
     * @return \EasyWeChat\OfficialAccount\Application|\EasyWeChat\Work\Application
     */
    public function officialAccount(array $config): \EasyWeChat\OfficialAccount\Application
    {
        $app = Factory::officialAccount($config);
        $handler = new CoroutineHandler();

        // 设置 HttpClient，部分接口直接使用了 http_client。
        $config = $app['config']->get('http', []);
        $config['handler'] = $stack = HandlerStack::create($handler);
        $app->rebind('http_client', new Client($config));
        $app->rebind('request', $this->getRequest());
        // 部分接口在请求数据时，会根据 guzzle_handler 重置 Handler
        $app['guzzle_handler'] = $handler;
        // 替换缓存
        $app['cache'] = $this->container->get(CacheInterface::class);
        $app->oauth->setGuzzleOptions([
            'http_errors' => false,
            'handler' => $stack,
        ]);
        return $app;
    }

    /**
     * 获取Request请求
     */
    private function getRequest(): Request
    {
        $request = $this->container->get(RequestInterface::class);
        $get = $request->getQueryParams();
        $post = $request->getParsedBody();
        $cookie = $request->getCookieParams();
        $uploadFiles = $request->getUploadedFiles() ?? [];
        $server = $request->getServerParams();
        $xml = $request->getBody()->getContents();
        $files = [];
        /** @var \Hyperf\HttpMessage\Upload\UploadedFile $v */
        foreach ($uploadFiles as $k => $v) {
            $files[$k] = $v->toArray();
        }
        $newRequest = new Request($get, $post, [], $cookie, $files, $server, $xml);
        $newRequest->headers = new HeaderBag($request->getHeaders());
        return $newRequest;
    }
}
