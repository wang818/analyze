<?php

namespace app\controllers;

use app\models\Session;
use app\components\Ypiao;
use app\components\Piaoniu;
use app\components\Tking;

class PriceListController extends \yii\web\Controller
{
    /**
     * @return string
     * 显示某个平台某场次的售价列表,实时抓取
     */
    public function actionIndex()
    {
        // sessionIds
        $sessionId = \Yii::$app->request->get('session_id');
        if (!$sessionId) {
            $this->goBack();
        }
        $sessionInfo = Session::findOne($sessionId);

        switch ($sessionInfo->source){
            case \Yii::$app->params['platform']['ypiao']:
                $source = '有票';
                $listArr = $this->getYpiaoPriceList($sessionInfo);
                break;
            case \Yii::$app->params['platform']['piaoniu']:
                $source = '票牛';
                $listArr = $this->getPiaoniuPriceList($sessionInfo);
                break;
            case \Yii::$app->params['platform']['tking']:
                $source = '摩天轮';
                $listArr = $this->getTkingPriceList($sessionInfo);
                break;
            default:
                $source = '其他';
                $this->goBack();
                break;
        }

        return $this->render('index', ['model' => $sessionInfo, 'list_arr' => $listArr, 'source' => $source]);
    }

    protected function getTkingPriceList($sessionInfo){
        $listArr = [];
        $tking = new Tking($sessionInfo->event->tking_id);
        $price = $tking->getSessionInfo($sessionInfo->third_session_id);
//        var_dump($price[0]->tickets);
        foreach ($price as $priceOne){
            $ones = [];
            foreach ($priceOne->tickets as $one){
                $_one = [
                    'price' => $one->price,
                ];
                $ones[] = $_one;
            }
            $_priceOne = [
                'id' => $priceOne->seatPlanOID,
                'name' => $priceOne->originalPrice . $priceOne->comments,
                'origin_price' => $priceOne->originalPrice,
                'price_list' => $ones
            ];

            $listArr[]= $_priceOne;
        }
        return $listArr;
    }

    /**
     * @param $sessionInfo
     * @return array
     * 获取有票的最新报价数组
     */
    protected function getYpiaoPriceList($sessionInfo){
        // 最终数组
        $listArr = [];
        $ypiao = new Ypiao($sessionInfo->event->youpiao_id);
        $ypiaoEvent = $ypiao->getEventData();
        $ypiaoSessionPriceList = $ypiao->getSessionDataBySecretSessionId($sessionInfo->third_session_id);
//                var_dump($ypiaoSessionPriceList);
        if (count($ypiaoEvent) > 0){
            foreach ($ypiaoSessionPriceList as $key => $item) {
                $price = $ypiao->getPriceBySecretPriceId($sessionInfo->third_session_id, $item->id);

                $listOne = [];
                $listOne['id'] = $item->id;
                $listOne['name'] = $item->name;
                $listOne['origin_price'] = $item->price;
                $listOne['price_list'] = $price;

                $listArr[]= $listOne;
            }
        }
        return $listArr;
    }

    protected function getPiaoniuPriceList($sessionInfo){
        $piaoniu = new Piaoniu($sessionInfo->event->piaoniu_id);
        $priceList = $piaoniu->getPriceList($sessionInfo->third_session_id);
//                var_dump($priceList);
        if (count($priceList) > 0){
            foreach ($priceList as $priceOne){
                $price = $piaoniu->getSellPriceList($sessionInfo->third_session_id, $priceOne->id);
                $_price = [];
                foreach ($price as $one){
                    $_one = [
                        'price' => $one->salePrice,
                        'seller' => $one->salerName
                    ];
                    $_price []= $_one;
                }
                $listOne = [];
                $listOne['id'] = $priceOne->id;
                $listOne['name'] = $priceOne->specification;
                $listOne['origin_price'] = $priceOne->originPrice;
                $listOne['price_list'] = $_price;

                $listArr[]= $listOne;
            }
        }
        return $listArr;
    }

}
