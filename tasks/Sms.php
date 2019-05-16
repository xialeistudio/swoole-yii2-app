<?php
/**
 * @author xialeistudio
 * @date 2019-05-16
 */

namespace app\tasks;


use yii\base\BaseObject;

/**
 * Class Sms
 * @package app\tasks
 */
class Sms extends BaseObject
{
    public function send($a, $b)
    {
        sleep(1);
        printf("%d %d\n", $a, $b);
        return 'ok';
    }
}