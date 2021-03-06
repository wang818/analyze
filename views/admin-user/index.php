<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdminUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admin Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Admin User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'password',
            'auth_key',
            'access_token',
            //'c_time:datetime',
            //'l_time:datetime',
            //'memo',
            //'phone',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
