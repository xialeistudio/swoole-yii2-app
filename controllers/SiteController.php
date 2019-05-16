<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 */

namespace app\controllers;

use app\tasks\Sms;
use Swoole\Http\Server;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\UserException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    public function actionIndex()
    {
        /** @var Server $server */
        $server = Yii::$app->get('server');
        return $server;
    }

    public function actionPost(int $id)
    {
        return [
            'id' => $id
        ];
    }

    public function actionJsonError()
    {
        throw new UserException('ss1');
    }

    public function actionHtmlError()
    {
        Yii::$app->response->format = Response::FORMAT_HTML;
        throw new UserException('ss');
    }

    /**
     * 投递任务
     * @return array
     * @throws InvalidConfigException
     */
    public function actionTask()
    {
        /** @var Server $server */
        $server = Yii::$app->get('server');
        $server->task([
            'handler' => [Sms::class, 'send'],
            'params' => ['1', '2']
        ]);
        return [];
    }
}
