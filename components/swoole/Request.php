<?php
/**
 * @author xialeistudio
 * @date 2019-05-16
 */

namespace app\components\swoole;


/**
 * swoole web请求
 * Class Request
 * @package app\components\swoole
 */
class Request extends \yii\web\Request
{
    /**
     * @var \Swoole\Http\Request
     */
    private $_request;

    /**
     * @return \Swoole\Http\Request
     */
    public function getRequest(): \Swoole\Http\Request
    {
        return $this->_request;
    }

    /**
     * @param \Swoole\Http\Request $request
     */
    public function setRequest(\Swoole\Http\Request $request)
    {
        $this->_request = $request;
        $this->setupRequest();
    }

    /**
     * 设置请求
     */
    public function setupRequest()
    {
        $this->getHeaders()->removeAll();
        foreach ($this->_request->header as $key => $value) {
            $this->getHeaders()->set($key, $value);
        }

        $_GET = $this->_request->get ?? [];
        $_POST = $this->_request->post ?? [];
        $_SERVER['REQUEST_METHOD'] = $this->_request->server['request_method'];
        $_SERVER['REQUEST_URI'] = $this->_request->server['request_uri'];
        $this->setBodyParams(null);
        $this->setRawBody($this->_request->rawContent());
        $this->setPathInfo($this->_request->server['path_info']);
    }
}