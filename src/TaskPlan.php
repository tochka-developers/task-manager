<?php

namespace Tochka\TaskManager;

use Tochka\TaskManager\Contracts\TaskPlanContract;
use Tochka\TaskManager\Facades\TaskProvider;

class TaskPlan implements TaskPlanContract
{
    private $tasks = [];
    private $planned_tasks = [];

    public function setTasks($tasks): void
    {
        $this->tasks = $tasks;
        $this->plan();
    }

    public function plan(): void
    {
        $points = [];
        foreach ($this->tasks as $task) {
            $taskHandler = TaskProvider::get($task->name);
            if (!$taskHandler) {
                continue;
            }

            $points[] = array_fill(0, $taskHandler->getPriority(), $task);
        }

        if (empty($points)) {
            return;
        }

        $this->planned_tasks = array_merge(...$points);

        shuffle($this->planned_tasks);
    }

    public function getPlannedTasks(): array
    {
        return $this->planned_tasks;
    }

    public function getTasks()
    {
        return $this->tasks;
    }
}
