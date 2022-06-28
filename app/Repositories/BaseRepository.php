<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace App\Repositories;

use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

/**
 * 仓库基类
 * Class BaseRepository.
 */
class BaseRepository
{
    /**
     * @var string
     */
    public $connection = 'default';

    /**
     * @var
     */
    public $model;

    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * __get
     * 可以实现通过仓库类自定义隐式注入需要注入的服务类 暂时不用.
     * @param $key
     * @return ContainerInterface|void
     */
    public function __get($key)
    {
        switch ($key) {
            case 'app':
                return $this->container;
                break;
            default:
                return $this->container;
        }
    }

    /**
     * 不存在方法时的处理  适用于模型创建.
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return make($this->model)->setConnection($this->connection)->getModel($this->model)->{$method}(...$parameters);
    }

    /**
     * 自定义链接.
     * @param string $connection
     */
    public function setConnection($connection = 'default'): BaseRepository
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * 自定义模型.
     * @param $model
     * @return BaseRepository
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * 获取详情.
     * @param array $filter 查询条件
     * @param array|string[] $columnArr 查询字段
     * @param array $orderBy 排序
     */
    public function getFirst(array $filter, array $columnArr = ['*'], $orderBy = []): array
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb)->select($columnArr);
        if (! empty($orderBy)) {
            foreach ($orderBy as $col => $direction) {
                $qb = $qb->orderBy($col, $direction);
            }
        }
        $data = $qb->first();
        return $data ? $data->toArray() : [];
    }

    /**
     * 获取列表.
     * @param array $filter 查询条件
     * @param array|string[] $columnArr 查询字段
     * @param int $page 页码
     * @param int $pageSize 每页展示条数
     * @param array $orderBy 排序
     */
    public function getList(array $filter, array $columnArr = ['*'], int $page = 1, int $pageSize = -1, array $orderBy = []): array
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb)->select($columnArr);
        if (! empty($orderBy)) {
            foreach ($orderBy as $col => $direction) {
                $qb = $qb->orderBy($col, $direction);
            }
        }
        if ($page > 0 && $pageSize > 0) {
            $qb = $qb->offset((($page - 1) * $pageSize))->limit($pageSize);
        }
        return $qb->get()->toArray();
    }

    /**
     * 获取带分页的列表.
     * @param array $filter 查询条件
     * @param array|string[] $columnArr 查询字段
     * @param int $page 页码
     * @param int $pageSize 每页展示条数
     * @param array $orderBy 排序
     */
    public function lists(array $filter, array $columnArr = ['*'], int $page = 1, int $pageSize = -1, array $orderBy = []): array
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb)->select($columnArr);
        if (! empty($orderBy)) {
            foreach ($orderBy as $col => $direction) {
                $qb = $qb->orderBy($col, $direction);
            }
        }
        if ($page > 0 && $pageSize > 0) {
            $qb = $qb->offset((($page - 1) * $pageSize))->limit($pageSize);
        }
        $list = $qb->paginate($pageSize, $columnArr, '', $page)
            ->toArray();
        return [
            'list' => $list['data'],
            'total_count' => $list['total'],
        ];
    }

    /**
     * 获取带分页的列表.
     * @param array $filter 查询条件
     * @param array|string[] $columnArr 查询字段
     * @param int $page 页码
     * @param int $pageSize 每页展示条数
     * @param array $orderBy 排序
     */
    public function listsBuilder(array $filter, array $columnArr = ['*'], int $page = 1, int $pageSize = -1, array $orderBy = []): array
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryBuilder($filter, $qb)->select($columnArr);
        if (! empty($orderBy)) {
            foreach ($orderBy as $col => $direction) {
                $qb = $qb->orderBy($col, $direction);
            }
        }
        if ($page > 0 && $pageSize > 0) {
            $qb = $qb->offset((($page - 1) * $pageSize))->limit($pageSize);
        }
        $list = $qb->paginate($pageSize, $columnArr, '', $page)
            ->toArray();
        return [
            'list' => $list['data'],
            'total_count' => $list['total'],
        ];
    }

    /**
     * 获取列表--原生
     * @param array $filter 查询条件
     * @param string $columns 查询字段
     * @param int $page 页码
     * @param int $pageSize 每页展示条数
     * @param array $orderBy 排序
     * @param array $data 原生语句
     * @return array|\Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection|\Hyperf\Database\Query\Builder[]|\Hyperf\Utils\Collection
     */
    public function getListRaw(array $filter, string $columns = '*', int $page = 1, int $pageSize = -1, array $orderBy = [], $data = [])
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb);
        if ($page > 0 && $pageSize > 0) {
            $qb->offset((($page - 1) * $pageSize))->limit($pageSize);
        }

        if (! empty($orderBy)) {
            foreach ($orderBy as $col => $direction) {
                $qb = $qb->orderBy($col, $direction);
            }
        }
        $data = $qb->selectRaw($columns, $data)->get();
        return is_array($data) ? $data : $data->toArray();
    }

    /**
     * 获取单个值
     * @param array $filter 查询条件
     * @param string $column 查询字段
     * @param array $orderBy 排序
     * @return \Hyperf\Utils\HigherOrderTapProxy|mixed|void
     */
    public function getValue(array $filter, string $column = '*', array $orderBy = [])
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb);
        if (! empty($orderBy)) {
            foreach ($orderBy as $col => $direction) {
                $qb = $qb->orderBy($col, $direction);
            }
        }
        return $qb->value($column);
    }

    /**
     * 获取一列.
     * @param array $filter 查询条件
     * @param string $columns 查询字段
     * @param int $page 页码
     * @param int $pageSize 每页展示条数
     * @param array $orderBy 排序
     */
    public function getPluck(array $filter = [], string $columns = '*', int $page = 1, int $pageSize = -1, array $orderBy = []): \Hyperf\Utils\Collection
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb);
        if ($page > 0 && $pageSize > 0) {
            $qb->offset((($page - 1) * $pageSize))->limit($pageSize);
        }
        if (! empty($orderBy)) {
            foreach ($orderBy as $col => $direction) {
                $qb = $qb->orderBy($col, $direction);
            }
        }
        return $qb->pluck($columns);
    }

    /**
     * 统计数量.
     * @param array $filter 条件
     */
    public function count(array $filter): int
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb);
        return $qb->count();
    }

    /**
     * 求和.
     * @param array $filter 条件
     * @param string $column 字段
     * @return int|mixed
     */
    public function sum(array $filter, string $column)
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb);
        return $qb->sum($column);
    }

    /**
     * 新增数据 不走model 修改器.
     * @param false $getId
     * @return mixed
     */
    public function insert(array $data, $getId = false)
    {
        if ($getId) {
            return make($this->model)->setConnection($this->connection)->insertGetId($data);
        }
        return make($this->model)->setConnection($this->connection)->insert($data);
    }

    /**
     * 走model修改器.
     * @return mixed
     */
    public function create(array $data)
    {
        $qb = make($this->model)->setConnection($this->connection);
        return $qb->create($data);
    }

    /**
     * 更新数据.
     * @param array $filter 条件
     * @param array $data 更新数据
     */
    public function updateBy(array $filter, array $data): int
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb);
        return $qb->update($data);
    }

    /**
     * 删除.
     * @param array $filter 条件
     * @return int|mixed
     */
    public function deleteBy(array $filter)
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb);
        return $qb->delete();
    }

    /**
     * 最大值
     * @param array $filter 条件
     * @param string $column 字段
     * @return mixed
     */
    public function max(array $filter, string $column)
    {
        $qb = make($this->model)->setConnection($this->connection)->query();
        $qb = queryFilter($filter, $qb);
        return $qb->max($column);
    }
}
