<?php

namespace Tochka\TaskManager;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Tochka\TaskManager\Console\TaskMakeMigration;
use Tochka\TaskManager\Console\TaskDisable;
use Tochka\TaskManager\Console\TaskEnable;
use Tochka\TaskManager\Console\TaskPause;
use Tochka\TaskManager\Console\TaskResume;
use Tochka\TaskManager\Console\TaskWatch;
use Tochka\TaskManager\Facades\WorkloadWatcher;
use Tochka\TaskManager\Workload\HorizonWorkload;

class TaskManagerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // публикуем конфигурации
        $this->publishes([
            __DIR__ . '/../config/task-manager.php' => App::basePath() . DIRECTORY_SEPARATOR . 'config/task-manager.php',
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                TaskDisable::class,
                TaskEnable::class,
                TaskMakeMigration::class,
                TaskPause::class,
                TaskResume::class,
                TaskWatch::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->app->singleton(WorkloadWatcher::class, static function () {
            $watcherName = Config::get('task-manager.workload_watcher', HorizonWorkload::class);

            return new $watcherName();
        });

        $this->app->singleton(\Tochka\TaskManager\Facades\TaskProvider::class, static function () {
            return new TaskProvider(Config::get('task-manager.log_channel', 'default'));
        });

        $this->app->singleton(\Tochka\TaskManager\Facades\TaskWatcher::class, static function () {
            return new TaskWatcher(Config::get('task-manager', []), new TaskPlan());
        });
    }
}
