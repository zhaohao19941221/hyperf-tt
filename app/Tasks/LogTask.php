<?php

declare(strict_types=1);
/**
 * This file is part of hyperf-tt.
 *
 * @link     https://github.com/zhaohao19941221/hyperf-tt
 * @document https://github.com/zhaohao19941221/hyperf-tt.git
 */
namespace App\Tasks;

use Exception;
use Hyperf\Task\Annotation\Task;

class LogTask
{
    /**
     * @Task
     */
    public function handle(): bool
    {
        try {
            return true;
        } catch (Exception $exception) {
        }
    }
}
