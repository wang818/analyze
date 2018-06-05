<?php

namespace app\controllers;

use Yii;
use app\models\Session;

class SessionController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $eventId = Yii::$app->request->get('event_id');
        if (!$eventId){
            $this->goBack();
        }
        $model = Session::find()->where(['event_id' => $eventId])
        ->asArray()
        ->all();
        return $this->render('index', ['model' => $model]);
    }

}
