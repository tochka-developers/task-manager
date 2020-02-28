<?php

namespace Tochka\TaskManager\Console;

class TaskPause extends TaskSwitcher
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:pause {task} {options?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pause some task';

    public function handle(): void
    {
        parent::handle();

        if ($this->taskInstance) {
            $this->taskInstance->is_active = false;
            $this->taskInstance->save();
        }
    }
}
