<?php

namespace Tochka\TaskManager\Contracts;

use Tochka\TaskManager\Models\Task;

interface TaskContract
{
    public const PRIORITY_NORMAL = 8;
    public const PRIORITY_SLOW = 4;
    public const PRIORITY_VERY_SLOW = 2;
    public const PRIORITY_BACKGROUND = 1;

    public function getName(): string;

    public function getWorkloadWeight(): int;

    public function getWorkloadQueues(): array;

    public function getPriority(): int;

    public function getRequiredArguments(): array;

    public function getHandler(): TaskHandlerContract;

    public function getTaskInstance(array $arguments): ?Task;
}
