<?php

namespace app\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AccountController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' =>['@']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'index' => ['get'],
//                    'create' => ['post', 'get'],
//                    'update' => ['post', 'get']
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

}
