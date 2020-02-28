<?php

namespace Tochka\TaskManager\Workload;

use Illuminate\Container\Container;
use Tochka\TaskManager\Contracts\WorkloadWatcherContract;
use Laravel\Horizon\Contracts\WorkloadRepository;

class HorizonWorkload implements WorkloadWatcherContract
{
    /** @var \Laravel\Horizon\Contracts\WorkloadRepository */
    private $workload_repository;

    /**
     * HorizonWorkload constructor.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        $this->workload_repository = Container::getInstance()->make(WorkloadRepository::class);
    }

    public function hasWorkload(array $queues, int $workload_weight): bool
    {
        foreach ($queues as $queueName => $queueMaxWorkload) {
            if ($this->getQueueWorkLoad($queueName) + $workload_weight > $queueMaxWorkload) {
                return true;
            }
        }

        return false;
    }

    /**
     * Возвращает текущую загрузку очереди
     *
     * @param string $queueName
     *
     * @return int
     */
    private function getQueueWorkLoad(string $queueName): int
    {
        $commonWorkload = $this->workload_repository->get();

        return array_reduce($commonWorkload, static function ($carry, $item) use ($queueName) {
            if (data_get($item, 'name') !== $queueName) {
                return $carry;
            }

            return data_get($item, 'length', 0);
        }, 0);
    }
}
