<?php

namespace Tochka\TaskManager\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static handle(\Tochka\TaskManager\Models\Task $task)
 * @method static \Tochka\TaskManager\Contracts\TaskContract|null get(string $name)
 * @method static register(\Tochka\TaskManager\Contracts\TaskContract $task)
 * @see \Tochka\TaskManager\TaskProvider
 */
class TaskProvider extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return self::class;
    }
}
