<?php
/**
 * @author xialeistudio
 * @date 2019-05-16
 */

namespace app\components\swoole;

/**
 * Swoole响应
 * Class SwooleResponse
 * @package app\components\web
 */
class Response extends \yii\web\Response
{
    /**
     * @var \Swoole\Http\Response
     */
    private $_response;

    /**
     * @return \Swoole\Http\Response
     */
    public function getResponse(): \Swoole\Http\Response
    {
        return $this->_response;
    }

    /**
     * @param \Swoole\Http\Response $response
     */
    public function setResponse(\Swoole\Http\Response $response)
    {
        $this->_response = $response;
    }

    /**
     * 发送响应头
     */
    protected function sendHeaders()
    {
        $headers = $this->getHeaders();
        foreach ($headers as $key => $values) {
            $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $key)));
            foreach ($values as $value) {
                $this->_response->header($name, $value);
            }
        }

        $this->_response->status($this->getStatusCode());
    }

    protected function sendContent()
    {
        if ($this->stream === null) {
            $this->content ? $this->_response->end($this->content) : $this->_response->end();
            return;
        }

        $size = 4 * 1024 * 1024;
        if (is_array($this->stream)) {
            list ($handle, $begin, $end) = $this->stream;
            fseek($handle, $begin);
            while (!feof($handle) && ($pos = ftell($handle)) <= $end) {
                if ($pos + $size > $end) {
                    $size = $end - $pos + 1;
                }
                // 同上
                $this->_response->write(fread($handle, $size));
                flush(); // Free up memory. Otherwise large files will trigger PHP's memory limit.
            }
            fclose($handle);
        } else {
            while (!feof($this->stream)) {
                $this->_response->write(fread($this->stream, $size));
                flush();
            }
            fclose($this->stream);
        }
        // 同上
        $this->_response->end();
    }
}