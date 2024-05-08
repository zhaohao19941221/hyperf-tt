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
use Hyperf\Cache\CacheManager;
use Hyperf\Cache\Driver\DriverInterface;
use Hyperf\Context\ApplicationContext;
use Hyperf\Database\Model\Builder;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

if (! function_exists('di')) {
    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/7 上午10:39
     * @Desc:获取Container
     * @param null|mixed $id
     * @return ContainerInterface|mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function di($id = null): mixed
    {
        $container = ApplicationContext::getContainer();
        if ($id) {
            return $container->get($id);
        }
        return $container;
    }
}

if (! function_exists('logger')) {
    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/7 上午10:39
     * @Desc:文件日志
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function logger(string $name = 'hyperf', string $group = 'default'): mixed
    {
        return di()->get(LoggerFactory::class)->get($name, $group);
    }
}

if (! function_exists('cache')) {
    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/7 上午10:40
     * @Desc:缓存实例 简单的缓存.
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function cache(string $name = 'default'): DriverInterface
    {
        return di()->get(CacheManager::class)->getDriver($name);
    }
}

if (! function_exists('format_throwable')) {
    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/7 上午10:40
     * @Desc:格式化错误异常
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    function format_throwable(Throwable $throwable): string
    {
        return di()->get(FormatterInterface::class)->format($throwable);
    }
}

if (! function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     */
    function storage_path(string $path = ''): string
    {
        return BASE_PATH . '/storage/' . $path;
    }
}

if (! function_exists('query_filter')) {
    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/7 上午10:42
     * @Desc:构造查询条件
     * @param mixed $qb
     */
    function query_filter(array $filter, $qb): Builder
    {
        if ($filter) {
            $operators = [
                'eq' => '=',
                'lt' => '<',
                'gt' => '>',
                'lte' => '<=',
                'gte' => '>=',
                'bet' => '<>',
                'neq' => '!=',
                '%' => 'like',
                'contains' => 'like',
                'like' => 'like',
                'in' => 'in',
                'notIn' => 'notIn',
                'notnull' => 'notnull',
                'null' => 'null',
                'or' => 'or',
                'ors' => 'ors',
                'orAnd' => 'orAnd',
                'orsAnd' => 'orsAnd',
            ];
            foreach ($filter as $field => $value) {
                $list = explode('|', $field);
                $column = $list[0];
                $list[1] = (count($list) > 1) ? ($operators[$list[1]] ?? false) : false;
                if (! $list[1]) {
                    $list[1] = '=';
                }
                switch ($list[1]) {
                    case 'in':
                        $qb = $qb->whereIn($column, $value);
                        break;
                    case 'notIn':
                        $qb = $qb->whereNotIn($column, $value);
                        break;
                    case 'bet':
                        $qb = $qb->whereBetween($column, $value);
                        break;
                    case 'like':
                        $qb = $qb->where($column, $list[1], '%' . $value . '%');
                        break;
                    case 'notnull':
                        $qb = $qb->whereNotNull($column);
                        break;
                    case 'null':
                        $qb = $qb->whereNull($column);
                        break;
                    case 'or':
                        $qb = $qb->where(function ($qb) use ($value) {
                            query_filter_or($value, $qb);
                        });
                        break;
                    case 'orAnd':
                        $qb = query_filter($value, $qb);
                        break;
                    case 'ors':
                        $qb = $qb->where(function ($qb) use ($value) {
                            query_builder_or($value, $qb);
                        });
                        break;
                    case 'orsAnd':
                        $qb = query_builder($value, $qb);
                        break;
                    default:
                        if (is_array($value)) {
                            $qb = $qb->whereIn($column, $value);
                        } else {
                            $qb = $qb->where($column, $list[1], $value);
                        }
                }
            }
        }
        return $qb;
    }
}

if (! function_exists('query_filter_or')) {
    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/7 上午10:42
     * @Desc:构造查询条件
     * @param mixed $qb
     */
    function query_filter_or(array $filter, $qb): Builder
    {
        if ($filter) {
            $operators = [
                'eq' => '=',
                'lt' => '<',
                'gt' => '>',
                'lte' => '<=',
                'gte' => '>=',
                'bet' => '<>',
                'neq' => '!=',
                '%' => 'like',
                'contains' => 'like',
                'like' => 'like',
                'in' => 'in',
                'notIn' => 'notIn',
                'notnull' => 'notnull',
                'null' => 'null',
                'or' => 'or',
                'ors' => 'ors',
                'orAnd' => 'orAnd',
                'orsAnd' => 'orsAnd',
            ];
            foreach ($filter as $field => $value) {
                $list = explode('|', $field);
                $column = $list[0];
                $list[1] = (count($list) > 1) ? ($operators[$list[1]] ?? false) : false;
                if (! $list[1]) {
                    $list[1] = '=';
                }
                switch ($list[1]) {
                    case 'in':
                        $qb = $qb->orWhereIn($column, $value);
                        break;
                    case 'notIn':
                        $qb = $qb->orWhereNotIn($column, $value);
                        break;
                    case 'bet':
                        $qb = $qb->orWhereBetween($column, $value);
                        break;
                    case 'like':
                        $qb = $qb->orWhere($column, $list[1], '%' . $value . '%');
                        break;
                    case 'notnull':
                        $qb = $qb->orWhereNotNull($column);
                        break;
                    case 'null':
                        $qb = $qb->orWhereNull($column);
                        break;
                    case 'or':
                        $qb = query_filter_or($value, $qb);
                        break;
                    case 'orAnd':
                        $qb = $qb->orWhere(function ($qb) use ($value) {
                            query_filter($value, $qb);
                        });
                        break;
                    case 'ors':
                        $qb = query_builder_or($value, $qb);
                        break;
                    case 'orsAnd':
                        $qb = $qb->where(function ($qb) use ($value) {
                            query_builder($value, $qb);
                        });
                        break;
                    default:
                        if (is_array($value)) {
                            $qb = $qb->orWhereIn($column, $value);
                        } else {
                            $qb = $qb->orWhere($column, $list[1], $value);
                        }
                }
            }
        }
        return $qb;
    }
}

if (! function_exists('query_builder')) {
    /**
     * 构造复杂查询条件.
     * @param array $where [['filter' => 'value]]
     * @param mixed $qb
     */
    function query_builder(array $where, $qb): mixed
    {
        if ($where) {
            foreach ($where as $filter) {
                $qb = $qb->where(function ($qb) use ($filter) {
                    query_filter($filter, $qb);
                });
            }
        }
        return $qb;
    }
}

if (! function_exists('query_builder_or')) {
    /**
     * 构造复杂查询条件.
     * @param array $where [['filter' => 'value]]
     * @param mixed $qb
     */
    function query_builder_or(array $where, $qb): mixed
    {
        if ($where) {
            foreach ($where as $filter) {
                $qb = $qb->orWhere(function ($qb) use ($filter) {
                    query_filter_or($filter, $qb);
                });
            }
        }
        return $qb;
    }
}

if (! function_exists('reload_route')) {
    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/7 上午10:44
     * @Desc:加载路由
     */
    function reload_route(): void
    {
        $path = BASE_PATH . '/routes';
        $dirs = scandir($path);
        foreach ($dirs as $dir) {
            if ($dir !== '.' && $dir !== '..') {
                $routeFilePath = $path . "/{$dir}";
                $files = scandir($routeFilePath);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        require_once $routeFilePath . '/' . basename($file);
                    }
                }
            }
        }
    }
}
