<?php

return [
    // таблица с тасками
    'tasks_table'          => [
        'connection' => null, //null = подключение по умолчанию
        'table'      => 'tasks',
    ],

    // канал для логирования
    'log_channel'          => 'default',

    // драйвер для проверки очередей на загрузку
    'workload_watcher'     => \Tochka\TaskManager\Workload\HorizonWorkload::class,

    // ожидание разгрузки очереди, сек
    'wait_workload'        => 10,
    // ожидание между выполнением задач, сек
    'wait_task'            => 1,
    // промежуток времени, через который необходимо актуализировать список задач, сек
    'update_tasks_timeout' => 60,
];
