<?php
/**
 * @author xialeistudio
 * @date 2019-05-16
 */

namespace app\components\swoole;

use Exception;
use Throwable;
use Yii;
use yii\base\ErrorException;
use yii\helpers\VarDumper;

/**
 * Swoole异常处理器
 * Class SwooleErrorHandler
 * @package app\components\swoole
 */
class ErrorHandler extends \yii\web\ErrorHandler
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function handleException($exception)
    {

        $this->exception = $exception;
        try {
            $this->logException($exception);
            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);
            if (!YII_ENV_TEST) {
                Yii::getLogger()->flush(true);
            }
        } catch (Exception $e) {
            // an other exception could be thrown while displaying the exception
            $this->handleFallbackExceptionMessage($e, $exception);
        } catch (Throwable $e) {
            // additional check for \Throwable introduced in PHP 7
            $this->handleFallbackExceptionMessage($e, $exception);
        }

        $this->exception = null;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function handleFallbackExceptionMessage($exception, $previousException)
    {
        $msg = "An Error occurred while handling another error:\n";
        $msg .= (string)$exception;
        $msg .= "\nPrevious exception:\n";
        $msg .= (string)$previousException;
        if (YII_DEBUG) {
            if (PHP_SAPI === 'cli') {
                echo $msg . "\n";
            } else {
                echo '<pre>' . htmlspecialchars($msg, ENT_QUOTES, Yii::$app->charset) . '</pre>';
            }
        } else {
            echo 'An internal server error occurred.';
        }
        $msg .= "\n\$_SERVER = " . VarDumper::export($_SERVER);
        throw new Exception($msg);
    }

    /**
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     * @return bool
     * @throws ErrorException
     * @throws Exception
     */
    public function handleError($code, $message, $file, $line)
    {
        if (error_reporting() & $code) {
            if (!class_exists('yii\\base\\ErrorException', false)) {
                require_once Yii::getAlias('@yii/base/ErrorException.php');
            }
            $exception = new ErrorException($message, $code, $code, $file, $line);
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            array_shift($trace);
            foreach ($trace as $frame) {
                if ($frame['function'] === '__toString') {
                    $this->handleException($exception);
                }
            }

            throw $exception;
        }

        return false;
    }

    public function handleFatalError()
    {
        if (!class_exists('yii\\base\\ErrorException', false)) {
            require_once Yii::getAlias('@yii/base/ErrorException.php');
        }

        $error = error_get_last();

        if (ErrorException::isFatalError($error)) {
            $exception = new ErrorException($error['message'], $error['type'], $error['type'], $error['file'],
                $error['line']);
            $this->exception = $exception;

            $this->logException($exception);

            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);

            // need to explicitly flush logs because exit() next will terminate the app immediately
            Yii::getLogger()->flush(true);
        }
    }

    /**
     * @param Exception $exception
     * @return array
     */
//    protected function convertExceptionToArray($exception)
//    {
//        $data = [
//            'errcode' => $exception->getCode(),
//            'errmsg' => $exception->getMessage()
//        ];
//        if (YII_DEBUG) {
//            $data['stack-trace'] = $exception->getTrace();
//        }
//        return $data;
//    }
}