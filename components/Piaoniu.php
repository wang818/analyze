<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/5/17
 * Time: 下午4:09
 */

namespace app\components;
use linslin\yii2\curl;

class Piaoniu
{
    /**
     * @var
     * 项目ID
     */
    public $eventId;

    /**
     * @var
     * 场次ID
     */
    public $sessionId;

    /**
     * @var
     * 票档ID
     */
    public $priceListId;

    /**
     * @var string
     * 接口主域名
     */
    public $host = 'https://api.piaoniu.com';

    /**
     * Piaoniu constructor.
     * @param $eventId
     *
     */
    function __construct($eventId)
    {
        $this->setEventId($eventId);
    }

    public function setEventId($eventId){
        $this->eventId = $eventId;
    }

    public function setSessionId($sessionId){
        $this->sessionId = $sessionId;
    }

    public function setPriceListId($priceListId){
        $this->priceListId = $priceListId;
    }

    /**
     * 获取场次列表
     */
    public function getSessions(){
        $curl = $this->_curl($this->getSessionListUrl());
        if ($curl->errorCode == null){
            $datas = json_decode($curl->response);
            if (is_array($datas->events))
                return $datas->events;
            else {
                return [];
            }
        }
    }

    /**
     * @param $sessionId
     * @return array|mixed
     * 获取到场次的票档列表
     */
    public function getPriceList($sessionId){
        if (!$sessionId) return [];
        $this->setSessionId($sessionId);
        $curl = $this->_curl($this->getPriceListUrl());
        if ($curl->errorCode == null){
            return json_decode($curl->response);
        } else {
            return [];
        }
    }

    /**
     * @param $sessionId
     * @param $priceListId
     * @return array
     * 获取到某一票档的售价列表
     */
    public function getSellPriceList($sessionId, $priceListId){
        if (!$sessionId || !$priceListId)
            return [];
        $this->setSessionId($sessionId);
        $this->setPriceListId($priceListId);
        $curl = $this->_curl($this->getSellPriceListUrl());
        if ($curl->errorCode == null){
            $datas = json_decode($curl->response);
            if (is_array($datas->data))
                return $datas->data;
            else {
                return [];
            }
        } else {
            return [];
        }
    }

    /**
     * @return string
     * 获取售票列表
     */
    protected function getSellPriceListUrl(){
        if ($this->sessionId && $this->priceListId){
            return  $this->host . '/v3/tickets?b2c=true&eventId='. $this->sessionId .'&pageIndex=1&pageSize=10&shopId=0&ticketCategoryId='. $this->priceListId;
        } else {
            return '';
        }
    }

    /**
     * @return string
     * 获取到获得场次列表的接口
     */
    protected function getSessionListUrl(){
        return $this->host . '/v3/activities/' . $this->eventId . '?areaTicketType=0&shopId=0';
    }

    /**
     * @return string
     * 获取得到票档列表的接口
     */
    protected function getPriceListUrl(){
        if ($this->sessionId)
            return $this->host . '/v3/ticketCategories?areaTicketType=2&b2c=true&eventId=' . $this->sessionId .'&shopId=0';
        else
            return '';
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