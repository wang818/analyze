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

        $sessionIds = explode(',', $sessionId);

        // 获取对比数组
        $compareArr = [];
        foreach ($sessionIds as $sessionId){
            $sessionInfo = Session::findOne($sessionId);
            switch ($sessionInfo->source){
                case \Yii::$app->params['platform']['ypiao']:
                    $listArr = $this->getYpiaoPriceList($sessionInfo);
                    break;
                case \Yii::$app->params['platform']['piaoniu']:
                    $listArr = $this->getPiaoniuPriceList($sessionInfo);
                    break;
                case \Yii::$app->params['platform']['tking']:
                    $listArr = $this->getTkingPriceList($sessionInfo);
                    break;
                default:
                    $this->goBack();
                    break;
            }
            $compareArr[]= $listArr;
        }
        $lastArr = $this->compareArr($compareArr);

        $eventInfo = $sessionInfo->event;

        return $this->render('index', ['event_info' => $eventInfo, 'last_arr' => $lastArr]);
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

            $priceList = [];
            $priceList[] = ['source' => $sessionInfo->source, 'price_list' => $ones];

            $_priceOne = [
                'name' => $priceOne->originalPrice . $priceOne->comments,
                'origin_price' => $priceOne->originalPrice,
                'price_list' => $priceList
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
        if (count($ypiaoEvent) > 0){
            foreach ($ypiaoSessionPriceList as $key => $item) {
                $price = $ypiao->getPriceBySecretPriceId($sessionInfo->third_session_id, $item->id);

                $priceList = [];
                $priceList[] = ['source' => $sessionInfo->source, 'price_list' => $price];

                $listOne = [];
                $listOne['name'] = $item->name;
                $listOne['origin_price'] = (float)($item->price);
                $listOne['price_list'] = $priceList;

                $listArr[]= $listOne;
            }
        }
        return $listArr;
    }

    protected function getPiaoniuPriceList($sessionInfo){
        $piaoniu = new Piaoniu($sessionInfo->event->piaoniu_id);
        $priceList = $piaoniu->getPriceList($sessionInfo->third_session_id);
        if (count($priceList) > 0){
            foreach ($priceList as $priceOne){
                $price = $piaoniu->getSellPriceList($sessionInfo->third_session_id, $priceOne->id);
                $_price = [];
                foreach ($price as $one){
                    if (is_null($one)) continue;
                    $_one = [
                        'price' => $one->salePrice,
                        'seller' => $one->salerName
                    ];
                    $_price []= $_one;
                }

                $priceList = [];
                $priceList[]= ['source' => $sessionInfo->source, 'price_list' => $_price];

                $listOne = [];
                $listOne['name'] = $priceOne->specification;
                $listOne['origin_price'] = $priceOne->originPrice;
                $listOne['price_list'] = $priceList;

                $listArr[]= $listOne;
            }
        }
        return $listArr;
    }

    /**
     * @param $compareArr
     * @return mixed
     * 形成对比数据
     */
    protected function compareArr($compareArr){
        if (count($compareArr) == 1){
            return $compareArr[0];
        }

        // 获取到索引origin_price
        $bigCount = 0;
        $bigIndex = -1;
        foreach ($compareArr as $i => $compare){
            if (count($compare) > $bigCount){
                $bigIndex = $i;
                $bigCount = count($compare);
            }
        }
        if ($bigIndex <= -1){
            return [];
        }
        // 确定以索引最多的数据为初始遍历模型
        $foreachArr = $compareArr[$bigIndex];
        unset($compareArr[$bigIndex]);
        $comparedArr = [];
        foreach ($foreachArr as $key => $arr){
            if (!in_array($arr['origin_price'], $comparedArr)){
                foreach ($compareArr as $compareArrOne){
                    foreach ($compareArrOne as $k => $item){
                        if ($item['origin_price'] == $arr['origin_price']){
                            // 需要合并list
                            $foreachArr[$key]['price_list'][]= $item['price_list'][0];
                            break;
                        }
                        $comparedArr[]= $arr['origin_price'];
                    }
                }
            }
        }
        return $foreachArr;
    }

}
