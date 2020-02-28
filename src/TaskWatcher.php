<?php

namespace Tochka\TaskManager;

use Tochka\TaskManager\Contracts\TaskPlanContract;
use Tochka\TaskManager\Facades\TaskProvider;
use Tochka\TaskManager\Facades\WorkloadWatcher;
use Tochka\TaskManager\Models\Task;

class TaskWatcher
{
    /** @var int Ожидание разгрузки очереди, в секундах */
    private $wait_workload;
    /** @var int Ожидание между выполнением задач */
    private $wait_task;
    /** @var int Промежуток времени, через который необходимо актуализировать список задач */
    private $update_tasks_timeout;

    /** @var TaskPlanContract План выполнения задач */
    private $task_plan;
    /** @var int Текущая итерация выполнения */
    private $iteration = 0;
    /** @var int Время последнего обновления задач из БД */
    private $last_task_list_updated = 0;

    public function __construct(array $config, TaskPlanContract $taskPlanContract)
    {
        $this->wait_workload = $config['wait_workload'] ?? 10;
        $this->wait_task = $config['wait_task'] ?? 1;
        $this->update_tasks_timeout = $config['update_tasks_timeout'] ?? 60;

        $this->task_plan = $taskPlanContract;
    }

    public function handle(): void
    {
        while (true) {
            $task = $this->getNextTask();

            // если задача не вернулась - значит либо в данный момент очередь перегружена, либо список задач пустой
            // в любом случае в такой ситуации используем увеличенный таймаут, чтобы часто не долбить и не создавать
            // лишнюю нагрузку
            if ($task) {
                TaskProvider::handle($task);
                sleep($this->wait_task);
            } else {
                sleep($this->wait_workload);
            }
        }
    }

    private function getNextTask(): ?Task
    {
        /** @var Task[] $planned_tasks */
        $planned_tasks = $this->task_plan->getPlannedTasks();

        if (
            empty($planned_tasks)
            || empty($this->task_plan->getTasks())
            || $this->last_task_list_updated + $this->update_tasks_timeout < time()
        ) {
            $this->updateTaskList();
        }

        if (empty($planned_tasks)) {
            return null;
        }

        if (!array_key_exists($this->iteration, $planned_tasks)) {
            $this->iteration = 0;
        }

        $currentTask = $planned_tasks[$this->iteration];
        $currentHandler = TaskProvider::get($currentTask->name);

        if ($currentHandler && WorkloadWatcher::hasWorkload($currentHandler->getWorkloadQueues(),
                $currentHandler->getWorkloadWeight())) {
            return null;
        }

        $this->iteration++;

        return $currentTask;
    }

    /**
     * Обновление списка задач из БД
     */
    private function updateTaskList(): void
    {
        $tasks = Task::where('is_active', true)
            ->get();

        $this->task_plan->setTasks($tasks);

        $this->last_task_list_updated = time();
    }
}
