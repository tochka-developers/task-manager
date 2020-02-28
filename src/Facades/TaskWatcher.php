<?php

namespace Tochka\TaskManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static handle()
 * @see \Tochka\TaskManager\TaskWatcher
 */
class TaskWatcher extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return self::class;
    }
}
