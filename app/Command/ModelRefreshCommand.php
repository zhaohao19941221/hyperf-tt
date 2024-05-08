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

namespace App\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

#[Command]
class ModelRefreshCommand extends HyperfCommand
{
    protected ContainerInterface $container;

    /**
     * 执行的命令行.
     *
     * @var ?string
     */
    protected ?string $name = 'model:refresh';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/7 下午3:41
     * @Desc:configure
     */
    public function configure(): void
    {
        parent::configure();
        $this->setDescription('刷新model属性');
    }

    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/7 下午3:41
     * @Desc:handle
     */
    public function handle(): void
    {
        $this->handleDirectoryFile(function ($pathName) {
            $pathInfo = pathinfo($pathName);
            $entity = str_replace(['/', 'app'], ['\\', ''], sprintf('%s%s%s', $pathInfo['dirname'], '/', $pathInfo['filename']));
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
        $model = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));
        foreach ($model as $val) {
            if (! is_file($val->getPathName())) {
                continue;
            }
            if (((! $needle) || str_contains($val->getPathName(), $needle)) && $callback) {
                $callback($val->getPathName());
            }
        }
    }
}
