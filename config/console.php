<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 */

return [
    'id' => 'asktao',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@app' => dirname(__DIR__)
    ],
    'components' => require __DIR__ . '/components.php',
    'params' => require __DIR__ . '/params.php'
];