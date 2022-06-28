<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;

if (! function_exists('di')) {
    /**
     * 获取Container.
     * @param null $id
     * @return mixed|\Psr\Container\ContainerInterface
     */
    function di($id = null)
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
     * 文件日志.
     * @param string $name
     * @param string $group
     * @return \Psr\Log\LoggerInterface
     */
    function logger($name = 'hyperf', $group = 'default')
    {
        return di()->get(LoggerFactory::class)->get($name, $group);
    }
}

if (! function_exists('is_true')) {
    /**
     * 将字符串中的bool转为布尔.
     * @param $val
     * @param bool $return_null
     * @return bool
     */
    function is_true($val, $return_null = false)
    {
        $boolVal = (is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $val);
        return $boolVal === null && ! $return_null ? false : $boolVal;
    }
}

if (! function_exists('cache')) {
    /**
     * 缓存实例 简单的缓存.
     * @param string $name
     * @return \Hyperf\Cache\Driver\DriverInterface
     */
    function cache($name = 'default')
    {
        return di()->get(Hyperf\Cache\CacheManager::class)->getDriver($name);
    }
}

if (! function_exists('format_throwable')) {
    /**
     * 格式化错误异常.
     */
    function format_throwable(Throwable $throwable): string
    {
        return di()->get(FormatterInterface::class)->format($throwable);
    }
}

if (! function_exists('replaceSpecialChar')) {
    /**
     * 去掉特殊字符.
     * @param $strParam
     * @return null|string|string[]
     */
    function replaceSpecialChar($strParam)
    {
        $regex = "/\\/|\\~|\\!|\\@|\\#|\\$|\\%|\\^|\\&|\\*|\\(|\\)|\\_|\\+|\\{|\\}|\\:|\\<|\\>|\\?|\\[|\\]|\\,|\\.|\\/|\\;|\\'|\\`|\\-|\\=|\\\\|\\|/";
        return preg_replace($regex, '', $strParam);
    }
}

if (! function_exists('getSignContent')) {
    /**
     * 拼接uri 用于验签等功能.
     * @param $params
     * @return string
     */
    function getSignContent($params)
    {
        ksort($params);
        $i = 0;
        $stringToBeSigned = '';
        foreach ($params as $k => $v) {
            if ($i == 0) {
                $stringToBeSigned .= "{$k}" . '=' . "{$v}";
            } else {
                $stringToBeSigned .= '&' . "{$k}" . '=' . "{$v}";
            }
            ++$i;
        }
        unset($k, $v);
        return $stringToBeSigned;
    }
}

if (! function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     * @param string $path
     * @return string
     */
    function storage_path($path = '')
    {
        return BASE_PATH . '/storage/' . $path;
    }
}

if (! function_exists('queryFilter')) {
    /**
     * 构造查询条件.
     * @param $qb
     * @return mixed
     */
    function queryFilter(array $filter, $qb): Hyperf\Database\Model\Builder
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
                            queryFilterOr($value, $qb);
                        });
                        break;
                    case 'orAnd':
                        $qb = queryFilter($value, $qb);
                        break;
                    case 'ors':
                        $qb = $qb->where(function ($qb) use ($value) {
                            queryBuilderOr($value, $qb);
                        });
                        break;
                    case 'orsAnd':
                        $qb = queryBuilder($value, $qb);
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

if (! function_exists('queryFilterOr')) {
    /**
     * 构造查询条件.
     * @param $qb
     * @return mixed
     */
    function queryFilterOr(array $filter, $qb): Hyperf\Database\Model\Builder
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
                        $qb = queryFilterOr($value, $qb);
                        break;
                    case 'orAnd':
                        $qb = $qb->orWhere(function ($qb) use ($value) {
                            queryFilter($value, $qb);
                        });
                        break;
                    case 'ors':
                        $qb = queryBuilderOr($value, $qb);
                        break;
                    case 'orsAnd':
                        $qb = $qb->where(function ($qb) use ($value) {
                            queryBuilder($value, $qb);
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

if (! function_exists('queryBuilder')) {
    /**
     * 构造复杂查询条件.
     * @param array $where [['filter' => 'value]]
     * @param $qb
     * @return mixed
     */
    function queryBuilder(array $where, $qb)
    {
        if ($where) {
            foreach ($where as $filter) {
                $qb = $qb->where(function ($qb) use ($filter) {
                    queryFilter($filter, $qb);
                });
            }
        }
        return $qb;
    }
}

if (! function_exists('queryBuilderOr')) {
    /**
     * 构造复杂查询条件.
     * @param array $where [['filter' => 'value]]
     * @param $qb
     * @return mixed
     */
    function queryBuilderOr(array $where, $qb)
    {
        if ($where) {
            foreach ($where as $filter) {
                $qb = $qb->orWhere(function ($qb) use ($filter) {
                    queryFilterOr($filter, $qb);
                });
            }
        }
        return $qb;
    }
}

if (! function_exists('reloadRoute')) {
    /**
     * 加载路由.
     */
    function reloadRoute()
    {
        $path = BASE_PATH . '/routes';
        $dirs = scandir($path);
        foreach ($dirs as $dir) {
            if ($dir != '.' && $dir != '..') {
                $routeFilePath = $path . "/{$dir}";
                $files = scandir($routeFilePath);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..') {
                        require_once $routeFilePath . '/' . basename($file);
                    }
                }
            }
        }
    }
}
