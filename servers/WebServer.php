<?php
/**
 * @author xialeistudio
 * @date 2019-05-16
 */

namespace app\servers;

use app\components\swoole\Application;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 * Web服务器
 * Class WebServer
 * @package app\servers
 */
class WebServer extends BaseObject
{
    /**
     * @var string 监听主机
     */
    public $host = 'localhost';
    /**
     * @var int 监听端口
     */
    public $port = 9501;
    /**
     * @var int 进程模型
     */
    public $mode = SWOOLE_PROCESS;
    /**
     * @var int SOCKET类型
     */
    public $sockType = SWOOLE_SOCK_TCP;
    /**
     * @var array 服务器选项
     */
    public $options = [
        'worker_num' => 2,
        'daemonize' => 0,
        'task_worker_num' => 2
    ];

    /**
     * @var Server
     */
    private $_server;

    public function init()
    {
        parent::init();
        $this->_server = new Server($this->host, $this->port, $this->mode, $this->sockType);
        $this->_server->set($this->options);

        foreach ($this->events() as $event => $callback) {
            $this->_server->on($event, $callback);
        }
    }

    /**
     * 事件监听
     * @return array
     */
    public function events()
    {
        return [
            'start' => [$this, 'onStart'],
            'workerStart' => [$this, 'onWorkerStart'],
            'workerError' => [$this, 'onWorkerError'],
            'request' => [$this, 'onRequest'],
            'task' => [$this, 'onTask']
        ];
    }

    /**
     * 启动服务器
     * @return bool
     */
    public function start()
    {
        return $this->_server->start();
    }

    /**
     * master启动
     * @param Server $server
     */
    public function onStart(Server $server)
    {
        printf("listen on %s:%d\n", $server->host, $server->port);
    }

    /**
     * 工作进程启动时实例化框架
     * @param Server $server
     * @param int $workerId
     * @throws InvalidConfigException
     */
    public function onWorkerStart(Server $server, $workerId)
    {
        $config = require __DIR__ . '/../config/web.php';
        new Application($config);
        Yii::$app->set('server', $server);
    }


    /**
     * 工作进程异常
     * @param Server $server
     * @param $workerId
     * @param $workerPid
     * @param $exitCode
     * @param $signal
     */
    public function onWorkerError(Server $server, $workerId, $workerPid, $exitCode, $signal)
    {
        fprintf(STDERR, "worker error. id=%d pid=%d code=%d signal=%d\n", $workerId, $workerPid, $exitCode, $signal);
    }

    /**
     * 处理请求
     * @param Request $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response)
    {
        Yii::$app->request->setRequest($request);
        Yii::$app->response->setResponse($response);
        Yii::$app->run();
        Yii::$app->response->clear();
    }

    /**
     * 分发任务
     * @param Server $server
     * @param $taskId
     * @param $workerId
     * @param $data
     * @return mixed
     */
    public function onTask(Server $server, $taskId, $workerId, $data)
    {
        list('handler' => $handler, 'params' => $params) = $data;
        list($class, $action) = $handler;

        $obj = new $class();
        return call_user_func_array([$obj, $action], $params);
    }
}