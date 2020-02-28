<?php

namespace Tochka\TaskManager\Support;

use Tochka\TaskManager\Contracts\TaskContract;
use Tochka\TaskManager\Contracts\TaskHandlerContract;
use Tochka\TaskManager\Models\Task;
use Illuminate\Support\Str;

/**
 * @property string $name
 * @property array  $workload_queues
 * @property int    $workload_weight
 * @property int    $priority
 * @property array  $required_arguments
 */
trait DefaultTask
{
    public function getName(): string
    {
        return $this->name ?? Str::snake(substr(strrchr(self::class, '\\'), 1));
    }

    public function getWorkloadQueues(): array
    {
        return $this->workload_queues ?? [];
    }

    public function getWorkloadWeight(): int
    {
        return $this->workload_weight ?? 100;
    }

    public function getPriority(): int
    {
        return $this->priority ?? TaskContract::PRIORITY_NORMAL;
    }

    public function getRequiredArguments(): array
    {
        return $this->required_arguments ?? [];
    }

    public function getHandler(): TaskHandlerContract
    {
        return $this;
    }

    public function getTaskInstance(array $arguments): ?Task
    {
        /** @var \Tochka\TaskManager\Models\Task[] $tasks */
        $tasks = Task::where('name', $this->getName())->get();

        $requiredArguments = $this->getRequiredArguments();

        foreach ($tasks as $task) {
            foreach ($requiredArguments as $requiredArgument) {
                if ($arguments[$requiredArgument] !== $task->args[$requiredArgument]) {
                    continue 2;
                }
            }

            return $task;
        }

        return null;
    }
}
