<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 */

use app\components\swoole\Request;
use app\components\swoole\Response;
use yii\gii\Module;

return [
    'id' => 'app',
    'name' => 'app',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'bootstrap' => ['gii', 'log'],
    'controllerNamespace' => 'app\controllers',
    'aliases' => [
        '@app' => dirname(__DIR__)
    ],
    'components' => array_merge(require __DIR__ . '/components.php', [
        'response' => [
            'class' => Response::class,
            'format' => Response::FORMAT_JSON
        ],
        'request' => [
            'class' => Request::class,
            'cookieValidationKey' => '123456'
        ]
    ]),
    'modules' => [
        'gii' => [
            'class' => Module::class,
        ],
    ],
    'params' => require __DIR__ . '/params.php'
];