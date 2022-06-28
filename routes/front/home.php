<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'HomeBundle\Controller\Front\V1\IndexController@index');
Router::addRoute(['GET', 'POST', 'HEAD'], '/favicon.ico', function () {
    return '';
});
