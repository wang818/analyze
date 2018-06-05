<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/1/3
 * Time: 下午3:51
 */

namespace app\controllers;

use Yii;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\web\Controller;

use app\models\Event;
use app\models\EventSearch;
use app\models\Session;
use app\models\PriceList;

use app\components\Piaoniu;
use app\components\Tking;
use app\components\Ypiao;

class EventController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'test-piaoniu', 'test-tking'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'test-piaoniu', 'test-tking'],
                        'allow' => true,
                        'roles' =>['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'index' => ['get'],
                    'create' => ['post', 'get'],
                    'update' => ['post', 'get']
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * event list page
     */
    public function actionIndex(){
        $searchModel = new EventSearch();
        $params = Yii::$app->request->get();
        $dataProvider = $searchModel->search($params);
        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * 同步有票的场次列表
     */
    public function actionRsyncYpiaoInfo(){
        $ypEventId = Yii::$app->request->get('yp_eventid');
        if ($ypEventId){

        }
    }

    public function actionTestPiaoniu(){
        $eventId = Yii::$app->request->get('event_id');
        if ($eventId){
            $piaoniuObj = new Piaoniu($eventId);
            $sessions = $piaoniuObj->getSessions();
            var_dump($sessions);
            $priceList = $piaoniuObj->getPriceList($sessions[0]->id);
            var_dump($priceList);
            $sellList = $piaoniuObj->getSellPriceList($sessions[0]->id, $priceList[0]->id);
            var_dump($sellList);
        }
    }

    public function actionTestTking(){
        $showId = Yii::$app->request->get('show_id');
        if ($showId){
            $tkingObj = new Tking($showId);
            $sessions = $tkingObj->getSessions();
            var_dump($sessions);
            $sessionInfo = $tkingObj->getSessionInfo($sessions[0]->showSessionOID);
            var_dump($sessionInfo);
        }
    }

    public function actionTestYpiao(){
        $eventId = Yii::$app->request->get('event_id');
        if ($eventId){
            $ypiaoObj = new Ypiao($eventId);
            $eventData = $ypiaoObj->getEventData();
            var_dump($eventData);
            $sessionData = $ypiaoObj->getSessionDataBySecretSessionId($eventData['sessionData'][0]->sessionSecretId);
            var_dump($sessionData);
//            $ticketList = $ypiaoObj->getPriceBySecretPriceId($eventData['sessionData'][0]->sessionSecretId, $sessionData[1]->id);
//            var_dump($ticketList);
        }
    }
    /**
     * create page and post data
     * @return string
     */
    public function actionCreate(){
        $model = new Event();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();

            /*同步有票场次列表*/
            $ypiaoEventId = $model->youpiao_id;
            $ypiaoObj = new Ypiao($ypiaoEventId);
            $ypiaoSessions = $ypiaoObj->getEventData();
            $model->youpiao_third_id = $ypiaoSessions['secretEventId'];
            $model->save();
            $source = Yii::$app->params['platform']['ypiao'];
            if (is_array($ypiaoSessions['sessionData']) && count($ypiaoSessions['sessionData']) > 0){
                foreach ($ypiaoSessions['sessionData'] as $ypiaoSession){
                    $sessionModel = new Session();
                    $startTime = $this->sessionNameToDateTime($ypiaoSession->sessionName);
                    $saveData = [
                        'name' => $ypiaoSession->sessionName,
                        'event_id' => $model->id,
                        'start_time' => $startTime,
                        'third_session_id' => $ypiaoSession->sessionSecretId,
                        'source' => $source
                    ];
                    $sessionModel = $this->loadArr($sessionModel, $saveData);
                    $sessionModel->save();

                    /* 保存票档 */
                    $priceLists = $ypiaoObj->getSessionDataBySecretSessionId($ypiaoSession->sessionSecretId);
                    if (is_array($priceLists) && count($priceLists) > 0){
                        foreach ($priceLists as $priceList){
                            $priceListModel = new PriceList();
                            $saveData = [
                                'price' => $priceList->price,
                                'event_id' => $model->id,
                                'session_id' => $sessionModel->id,
                                'third_price_id' => $priceList->id,
                                'name' => $priceList->name
                            ];
                            $priceListModel = $this->loadArr($priceListModel, $saveData);
                            $priceListModel->save();
                        }
                    }
                }
            }

            /*同步票牛场次列表*/
            $piaoniuEventId = $model->piaoniu_id;
            $piaoniuObj = new Piaoniu($piaoniuEventId);
            $piaoniuSessions = $piaoniuObj->getSessions();
            $source = Yii::$app->params['platform']['piaoniu'];
            if (is_array($piaoniuSessions) && count($piaoniuSessions) > 0){
                foreach ($piaoniuSessions as $piaoniuSession){
                    $sessionModel = new Session();
                    $startTime = $this->sessionNameToDateTime($piaoniuSession->specification);
                    $saveData = [
                        'name' => $piaoniuSession->specification,
                        'event_id' => $model->id,
                        'start_time' => $startTime,
                        'third_session_id' => (string)$piaoniuSession->id,
                        'source' => $source
                    ];
                    $sessionModel = $this->loadArr($sessionModel, $saveData);
                    $re = $sessionModel->save();
                    /* 保存票档 */
                    $priceLists = $piaoniuObj->getPriceList($piaoniuSession->id);
                    if (is_array($priceLists) && count($priceLists) > 0){
                        foreach ($priceLists as $priceList){
                            $priceListModel = new PriceList();
                            $saveData = [
                                'price' => $priceList->originPrice,
                                'event_id' => $model->id,
                                'session_id' => $sessionModel->id,
                                'third_price_id' => (string)$priceList->id,
                                'name' => $priceList->specification
                            ];
                            $priceListModel = $this->loadArr($priceListModel, $saveData);
                            $priceListModel->save();
                        }
                    }

                }
            }

            /*同步摩天轮场次列表*/
            $tkingEventId = $model->tking_id;
            $tkingObj = new Tking($tkingEventId);
            $tkingSessions = $tkingObj->getSessions();
            $source = Yii::$app->params['platform']['tking'];
            if (is_array($tkingSessions) && count($tkingSessions) > 0){
                foreach ($tkingSessions as $tkingSession){
                    $sessionModel = new Session();
                    $startTime = $this->sessionNameToDateTime($tkingSession->sessionName);
                    $saveData = [
                        'name' => $tkingSession->sessionName,
                        'event_id' => $model->id,
                        'start_time' => $startTime,
                        'third_session_id' => $tkingSession->showSessionOID,
                        'source' => $source
                    ];
                    $sessionModel = $this->loadArr($sessionModel, $saveData);
                    $sessionModel->save();

                    /* 保存票档 */
                    $priceLists = $tkingObj->getSessionInfo($tkingSession->showSessionOID);
                    if (is_array($priceLists) && count($priceLists) > 0){
                        foreach ($priceLists as $priceList){
                            $priceListModel = new PriceList();
                            $saveData = [
                                'price' => $priceList->originalPrice,
                                'event_id' => $model->id,
                                'session_id' => $sessionModel->id,
                                'third_price_id' => $priceList->seatPlanOID,
                                'name' => $priceList->originalPrice . $priceList->comments
                            ];
                            $priceListModel = $this->loadArr($priceListModel, $saveData);
                            $priceListModel->save();
                        }
                    }
                }
            }

            $this->redirect('?r=event/index');
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    protected function sessionNameToDateTime($name){
        $startTimeArr = explode(' ', $name);
        $startTime = date("Y-m-d H:i:s", strtotime($startTimeArr[0] . ' ' . $startTimeArr[2]));
        return $startTime;
    }

    protected function loadArr($model, $arr){
        foreach ($arr as $key => $value){
            $model->$key = $value;
        }
        return $model;
    }

    /**
     *  update
     */
    public function actionUpdate($id){
        $model = $this->findModelById($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()){
            $this->redirect('?r=event/index');
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * @param $id
     * @return bool|null|static
     */
    protected function findModelById($id){
        $model = Event::findOne(['id' => $id]);
        if ($model != null) {
            return $model;
        } else {
            return false;
        }
    }
}