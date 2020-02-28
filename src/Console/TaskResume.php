<?php

namespace Tochka\TaskManager\Console;

class TaskResume extends TaskSwitcher
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:resume {task} {options?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resume some task';

    public function handle(): void
    {
        parent::handle();

        if ($this->taskInstance) {
            $this->taskInstance->is_active = true;
            $this->taskInstance->save();
        }
    }
}
