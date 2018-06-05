<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/5/18
 * Time: 下午2:48
 */

namespace app\components;
use linslin\yii2\curl;


class Tking
{
    public $host = "https://www.tking.cn";

    public $showId;

    public $sessionId;

    function __construct($showId)
    {
        $this->setShowId($showId);
    }

    public function setShowId($showId){
        $this->showId = $showId;
    }

    public function setSessionId($sessionId){
        $this->sessionId = $sessionId;
    }

    public function getSessions(){
        if (!$this->showId) return [];
        $curl = $this->_curl($this->getSessionsUrl());
        if ($curl->errorCode == null){
            $datas = json_decode($curl->response);
            return $datas->result->data;
        }
    }

    public function getSessionInfo($sessionId){
        if (!$sessionId) return [];
        $this->setSessionId($sessionId);
        $curl = $this->_curl($this->getSessionInfoUrl());
        if ($curl->errorCode == null){
            $datas = json_decode($curl->response);
            return $datas->result->data;
        }
    }

    protected function getSessionsUrl(){
        if ($this->showId){
            return $this->host . '/showapi/pub/show/' . $this->showId . '/sessionone?src=web';
        } else {
            return '';
        }

    }

    protected function getSessionInfoUrl(){
        if ($this->sessionId){
            return $this->host . '/showapi/pub/showSession/' . $this->sessionId . '/seatplans/sale?src=web';
        }
    }

    protected function _curl($url, $params = []){
        $curl = new curl\Curl();
        if (count($params) > 0 ){
            // post
            $curl->setPostParams($params)->post($url);
        } else{
            // get
            $curl->get($url);
        }
        return $curl;
    }
}