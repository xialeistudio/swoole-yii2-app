<?php
/**
 * Swoole服务器配置
 * @author xialeistudio
 * @date 2019-05-16
 */
return [
    'host' => 'localhost',
    'port' => 9501,
    'mode' => SWOOLE_PROCESS,
    'sockType' => SWOOLE_SOCK_TCP,
    'options' => [
        'pid_file' => Yii::getAlias('@runtime/swoole.pid'),
        'worker_num' => 2,
        'daemonize' => 0,
        'task_worker_num' => 2,
    ]
];