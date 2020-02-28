<?php

namespace Tochka\TaskManager;

use Illuminate\Support\Facades\Log;
use Tochka\TaskManager\Contracts\TaskContract;
use Tochka\TaskManager\Models\Task;

class TaskProvider
{
    private $log_channel;
    /** @var TaskContract[] */
    private $tasks = [];

    public function __construct(string $log_channel)
    {
        $this->log_channel = $log_channel;
    }

    public function register(TaskContract $task): void
    {
        $this->tasks[$task->getName()] = $task;
    }

    public function handle(Task $task): void
    {
        if (!array_key_exists($task->name, $this->tasks)) {
            return;
        }

        $taskInstance = $this->tasks[$task->name];
        try {
            $taskInstance->getHandler()->handle($task);
        } catch (\Exception $e) {
            Log::channel($this->log_channel)
                ->error($e->getMessage(), [
                    'exception_code' => $e->getCode(),
                    'exception_file' => $e->getFile() . ':' . $e->getLine(),
                    'caller'         => self::class,
                ]);
        }
    }

    public function get(string $name): ?TaskContract
    {
        if (!array_key_exists($name, $this->tasks)) {
            return null;
        }

        return $this->tasks[$name];
    }
}
