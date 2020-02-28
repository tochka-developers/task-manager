<?php

namespace Tochka\TaskManager\Console;

use Tochka\TaskManager\Models\Task;

class TaskEnable extends TaskSwitcher
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:enable {task} {options?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable some task';

    public function handle(): void
    {
        parent::handle();

        if (!$this->taskInstance) {
            $task = new Task();
            $task->name = $this->taskHandler->getName();
            $task->last = 0;
            $task->args = $this->arguments;
            $task->save();
        }
    }
}
