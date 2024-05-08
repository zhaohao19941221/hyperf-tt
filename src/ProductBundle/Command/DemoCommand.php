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

namespace ProductBundle\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;

#[Command]
class DemoCommand extends HyperfCommand
{
    protected ContainerInterface $container;

    /**
     * 执行的命令行.
     *
     * @var ?string
     */
    protected ?string $name = 'domo:domo';

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
        $this->setDescription('domo');
    }

    /**
     * @Created By: zhaohao
     * @Created At: 2024/5/7 下午3:41
     * @Desc:handle
     */
    public function handle(): void
    {
    }
}
