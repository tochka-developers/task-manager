<?php

namespace Tochka\TaskManager\Console;

class TaskDisable extends TaskSwitcher
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:disable {task} {options?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable some task';

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        parent::handle();

        if ($this->taskInstance) {
            $this->taskInstance->delete();
        }
    }
}
