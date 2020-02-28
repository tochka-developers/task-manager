<?php

namespace Tochka\TaskManager\Contracts;

use Tochka\TaskManager\Models\Task;

interface TaskHandlerContract
{
    public function handle(Task $task);
}
