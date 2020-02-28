<?php

namespace Tochka\TaskManager\Console;

use Tochka\TaskManager\Facades\TaskWatcher;
use Illuminate\Console\Command;

class TaskWatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watcher for tasks';

    public function handle(): void
    {
        TaskWatcher::handle();
    }
}
