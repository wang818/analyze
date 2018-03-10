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

class EventController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update'],
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
     * create page and post data
     * @return string
     */
    public function actionCreate(){
        $model = new Event();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            $this->redirect('?r=event/index');
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
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