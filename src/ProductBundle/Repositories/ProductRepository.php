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

namespace ProductBundle\Repositories;

use App\Repositories\BaseRepository;

use function Hyperf\Support\make;

class ProductRepository extends BaseRepository
{
    //    public $model = Product::class;
    //
    //    /**
    //     * 链接 重写时可自定义替换.
    //     * @var string
    //     */
    //    public string $connection = 'default';
    //
    //    /**
    //     * 获取每期所有的分摊数量.
    //     * @return mixed
    //     */
    //    public function getNum(): mixed
    //    {
    //        return make($this->model)->setConnection($this->connection)
    //            ->select('helps_show_id')
    //            ->get()->toArray();
    //    }
}
