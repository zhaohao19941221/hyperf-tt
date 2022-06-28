<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace App\Controller;

use App\Exception\FrontException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var Response
     */
    protected $response;

    /**
     * 获取分区ie.
     * @return mixed
     */
    protected function getZoneId()
    {
        $zoneId = $this->request->getAttribute('zoneid');
        if (empty($zoneId)) {
            throw new FrontException(123);
        }
        return $zoneId;
    }

    /**
     * 获取玩家id.
     * @return mixed
     */
    protected function getRoleId()
    {
        $roleId = $this->request->getAttribute('roleid');
        if (empty($roleId)) {
            throw new FrontException(123);
        }
        return $roleId;
    }
}
