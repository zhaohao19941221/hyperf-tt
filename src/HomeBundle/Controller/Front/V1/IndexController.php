<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace HomeBundle\Controller\Front\V1;

use App\Controller\AbstractController;

/**
 * Class IndexController.
 */
class IndexController extends AbstractController
{
    public function index()
    {
        return ['message' => 'hello 用户'];
    }

    public function getImg()
    {
        $imgUrl = $this->request->input('img');
        return $imgUrl ? file_get_contents(urldecode($imgUrl)) : '';
    }
}
