<?php

namespace Tochka\TaskManager\Contracts;

interface WorkloadWatcherContract
{
    public function hasWorkload(array $queues, int $workload_weight): bool;
}
