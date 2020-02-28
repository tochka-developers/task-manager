<?php

namespace Tochka\TaskManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool hasWorkload(array $queues, int $workload_weight)
 * @see \Tochka\TaskManager\Workload\HorizonWorkload
 */
class WorkloadWatcher extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return self::class;
    }
}
