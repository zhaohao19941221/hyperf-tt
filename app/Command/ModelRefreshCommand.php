<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace App\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;

/**
 * 刷新model属性.
 * @Command
 */
class ModelRefreshCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * 执行的命令行.
     *
     * @var string
     */
    protected $name = 'model:refresh';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('刷新model属性');
    }

    public function handle()
    {
        $this->handleDirectoryFile(function ($pathName) {
            $pathInfo = pathinfo($pathName);
            $entity = str_replace('/', '\\', sprintf('%s%s%s', $pathInfo['dirname'], '/', $pathInfo['filename']));
            $entity = str_replace('app', '', $entity);
            if (class_exists($entity)) {
                $model = new $entity();
                $this->info($model->getModel()->getTable() . 'model开始刷新');
                $this->call('gen:model', [
                    'table' => $model->getModel()->getTable(),
                    '--path' => $pathInfo['dirname'],
                ]);
                $this->info($model->getModel()->getTable() . 'model刷新成功');
            }
        }, 'app', 'Model');
    }

    /**
     * 处理目录文件.
     * @param callable $callback 闭包方法
     * @param string $baseDir 基础目录
     * @param string $needle 需要判断目录的条件
     */
    public function handleDirectoryFile(callable $callback, string $baseDir = 'app', string $needle = ''): void
    {
        $model = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($baseDir));
        foreach ($model as $key => $val) {
            if (! is_file($val->getPathName())) {
                continue;
            }
            if (((! $needle) || (strpos($val->getPathName(), $needle) !== false)) && $callback) {
                $callback($val->getPathName());
            }
        }
    }
}
