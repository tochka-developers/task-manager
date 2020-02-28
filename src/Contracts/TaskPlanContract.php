<?php

namespace Tochka\TaskManager\Contracts;

interface TaskPlanContract
{
    public function getTasks();

    public function setTasks($tasks): void;

    public function getPlannedTasks(): array;
}
