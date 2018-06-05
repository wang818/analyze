<?php

/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/4/9
 * Time: 下午2:24
 */
namespace app\components;

use \Curl\Curl;
use PHPHtmlParser\Dom;

class Ypiao
{
    /**
     * ypiao event id
     * @var int
     */
    public $eventId;

    /**
     * @var encode event id
     */
    public $secretEventId;

    /**
     * @var ypiao event page url
     */
    public $pageHost = "http://www.ypiao.com/t_";

    /**
     * @var string get session data api
     */
    public $priceStockApi = "http://www.ypiao.com/ticket/get-price-stock/";

    /**
     * @var string get price list by ticket num
     */
    public $priceListApi = "http://www.ypiao.com/ticket/get-rapid-list/";

    /**
     * @var api host
     */
    public $apiHost = "http://api.ypiao.com";

    /**
     * @var string event info api
     */
    public $eventShow = "/event/show.json";

    /**
     * @var string platform
     */
    public $platform = "4";

    /**
     * @var get from event page cookies
     */
    public $cookie_csrf;

    /**
     * @var get from event page
     */
    public $_csrf;

    /**
     * @var sessions data
     */
    public $sessionData;

    /**
     * ypiao constructor.
     * @param $eventId
     */
    function __construct($eventId)
    {
        $this->eventId = $eventId;
        //$this->getEventData();
    }

    /**
     * get event page info
     * @return array
     */
    public function getEventData(){
        if (!$this->eventId) return false;
        $url = $this->pageHost . $this->eventId . '/';
        //echo $url;
        $result = $this->curlData($url, [], 'get', true);
        $htmlStr = preg_replace("/<script[\s\S]*?<\/script>/i","",$result['data']);
        $htmlStr = preg_replace("/<style[\s\S]*?<\/style>/i","",$htmlStr);
        $html = new Dom;
        @$html->load($htmlStr);
        $returnData = [];
        // csrf
        $csrf = $html->find('input[id=_csrf]');
        $this->_csrf = $returnData['_csrf'] = $csrf[0]->value;
        // cookie_csrf
        $returnData['cookie_csrf'] = $this->cookie_csrf = $result['cookies']['_csrf'];
        // secret event id
        $secretEventId = $html->find('input[id=event_id]');
        $returnData['secretEventId'] = $this->secretEventId = $secretEventId[0]->value;

        // session secret id
        $secretSessionIdsObj = $html->find('.getid');
        $secretSessions = [];
        foreach ($secretSessionIdsObj as $key => $item) {
            $obj = (object)[];
            $obj->sessionName = $item->innerHtml;
            $obj->sessionName = preg_replace("/<input.*?\/>/", '',  $obj->sessionName);
            $obj->sessionSecretId = $item->find('input')[0]->id;
            $obj->sessionSecretId = str_replace('sid', '', $obj->sessionSecretId);
            $secretSessions[]= $obj;
        }
        $this->sessionData = $secretSessions;
        $returnData['sessionData'] = $secretSessions;
        return $returnData;
    }

    /**
     * get session price data
     * @param $secretSessionId
     * @return bool|mixed
     */
    public function getSessionDataBySecretSessionId($secretSessionId){
        $url = $this->priceStockApi;
        if (!$this->secretEventId || !$secretSessionId || !$this->cookie_csrf) return false;

        $params = [
            'event_id' => $this->secretEventId,
            'session_id' => $secretSessionId,
            '_csrf' => $this->_csrf,
            'start' => 0
        ];

        $priceData = $this->curlData($url, $params, 'post', false, ['_csrf' => $this->cookie_csrf]);
        $datas = json_decode($priceData['data']);
        return $datas->list;
    }

    /**
     * get price list by diff num
     * @param $secretSessionId
     * @param $secretPriceId
     * @return string
     */
    public function getPriceBySecretPriceId($secretSessionId, $secretPriceId){
        if (!$this->eventId || !$secretSessionId || !$secretPriceId || !$this->cookie_csrf) return flase;
        $url = $this->priceListApi;
        $params = [
            'event_id' => $this->secretEventId,
            'session_id' => $secretSessionId,
            'event_price_id' => $secretPriceId,
            '_csrf' => $this->_csrf
        ];
        $listData = $this->curlData($url, $params, 'post', false, ['_csrf' => $this->cookie_csrf]);
        return json_decode($listData['data'], true);
    }

    /**
     * 生成签名
     * @param array
     * @return string
     */
    public function createSign($params){
        ksort($params);
        $string = implode("#",$params);
        $string = $string . "#" . $this->appSecret;
        return md5($string);
    }

    /**
     * @param $url
     * @param $data array
     * @param string $method
     * @return string
     */
    public function curlData($url, $data=[], $method="get", $getCookie= false, $setCookie=[]){
        $curl = new Curl();

        if (!empty($setCookie)){
            $curl->setCookies($setCookie);
        }

        if ($method == 'get'){
            $result = $curl->get($url);
        }
        if ($method == 'post'){
            $result = $curl->post($url, $data);
        }
        $return = [];

        if ($getCookie){
            $return['cookies'] = $curl->responseCookies;
        }

        $return['data'] = $result;
        return $return;
    }
}