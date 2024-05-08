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

namespace ProductBundle\Service;

use Hyperf\Di\Annotation\Inject;
use ProductBundle\Repositories\ProductRepository;

/**
 * @Created By: zhaohao
 * @Created At: 2024/5/8 下午3:57
 * @Desc:商品服务类
 */
class ProductService
{
    #[Inject]
    public ProductRepository $productRepository;

    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/8 下午3:58
     * @Desc:获取列表
     */
    public function getList(array $filter, array $columnArr = ['*'], int $page = 1, int $pageSize = -1, array $orderBy = []): array
    {
        return $this->productRepository->getList($filter, $columnArr, $page, $pageSize, $orderBy);
    }
}
