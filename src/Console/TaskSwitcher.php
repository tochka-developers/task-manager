<?php

namespace Tochka\TaskManager\Console;

use Tochka\TaskManager\Facades\TaskProvider;
use Illuminate\Console\Command;

abstract class TaskSwitcher extends Command
{
    protected $arguments = [];
    /** @var \Tochka\TaskManager\Contracts\TaskContract */
    protected $taskHandler;
    /** @var \Tochka\TaskManager\Models\Task */
    protected $taskInstance;

    public function handle(): void
    {
        $taskName = $this->argument('task');
        $options = $this->argument('options');

        $this->arguments = $this->getInputArguments($options);
        $this->taskHandler = TaskProvider::get($taskName);

        if (!$this->taskHandler) {
            throw new \RuntimeException(sprintf('Task not found: [%s]', $taskName));
        }

        $requiredArguments = $this->taskHandler->getRequiredArguments();
        $notPresentArguments = array_diff($requiredArguments, array_keys($this->arguments));

        if (!empty($notPresentArguments)) {
            throw new \RuntimeException(sprintf('Не указаны обязательные параметры: [%s]',
                implode(',', $notPresentArguments)));
        }

        $this->taskInstance = $this->taskHandler->getTaskInstance($this->arguments);
    }

    public function getInputArguments(array $input): array
    {
        $result = [];
        foreach ($input as $parameter) {
            [$key, $value] = explode('=', $parameter);
            $result[$key] = $value;
        }

        return $result;
    }
}
