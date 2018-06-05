<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/5/17
 * Time: 下午3:03
 */

namespace app\components;

use linslin\yii2\curl;

class Damai
{
    /**
     * @var
     * da mai event id
     */
    public $eventId;

    public $host = '';

    function __construct($eventId)
    {
        $this->eventId = $eventId;
    }


}