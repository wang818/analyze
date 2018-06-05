<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/1/3
 * Time: 下午7:20
 */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = '项目列表';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
    .list_box{
        width:100%;
        overflow: auto;
    }
CSS;


$this->registerCss($css);
?>
<div class="site-about">
    <?= Html::a('创建项目',['event/create'], ['class' => 'btn btn-primary']) ?>
</div>
<div class="list_box">
    <?= GridView::widget([
        'filterModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'name',
            ['header' => 'other', 'format' => 'raw', 'value' => function($model){
                $str = '';
                $str .= Html::a('同步', ['event/rsync-ypiao-info','yp_eventid'=>$model->youpiao_id]) . " ";
                $str .= Html::a('场次', ['session/index', 'event_id' => $model->id]);
                return $str;
            }],
            ['header' => '友情链接','format' => 'raw', 'value' => function($model){
                $str = '';
                if ($model->damai_id){
                    $str .= Html::a('大麦', \Yii::$app->params['damaiHost'] . '/' . $model->damai_id . '.html', ['target' => '_blank']) . " ";
                }
                if ($model->yongle_id)
                    $str .= Html::a('永乐', \Yii::$app->params['yongleHost'] . '/ticket-' . $model->yongle_id .'.html', ['target' => '_blank']). " ";
                if ($model->piaoniu_id)
                $str .= Html::a('票牛', \Yii::$app->params['piaoniuHost'] . '/activity/' . $model->piaoniu_id, ['target' => '_balnk']). " ";
                if ($model->tking_id)
                    $str .= Html::a('摩天轮', \Yii::$app->params['tkingHost'] . '/content/' . $model->tking_id, ['target' => '_balnk']). " ";

                if ($model->xishiqu_id){
                    $valueArr = explode(',', $model->xishiqu_id);
                    if (count($valueArr) > 0) {
                        $str .= Html::a('西十区', \Yii::$app->params['xishiquHost'] . '/event/' . $valueArr[0] . '/all/p1.html?specialId=' . $valueArr[1], ['target' => '_balnk']). " ";
                    }
                }
                if ($model->youpiao_id)
                    $str .= Html::a('有票', \Yii::$app->params['youpiaoHost'] . '/t_' . $model->youpiao_id, ['target' => '_balnk']);

                return $str;
            }],
//            ['attribute' => 'yongle_id', 'format' => 'raw', 'value' => function($model){
//                $str = Html::a('永乐', \Yii::$app->params['yongleHost'] . '/ticket-' . $model->yongle_id .'.html', ['target' => '_blank']);
//                return $str;
//            }],
//            ['attribute' => 'piaoniu_id', 'format' => 'raw', 'value' => function($model){
//                $str = Html::a('票牛', \Yii::$app->params['piaoniuHost'] . '/activity/' . $model->piaoniu_id, ['target' => '_balnk']);
//                return $str;
//            }],
//            ['attribute' => 'xishiqu_id', 'format' => 'raw', 'value' => function($model){
//                $valueArr = explode(',', $model->xishiqu_id);
//                if (count($valueArr) > 0){
//                    $str = Html::a('西十区', \Yii::$app->params['xishiquHost'] . '/event/' . $valueArr[0] . '/all/p1.html?specialId=' . $valueArr[1], ['target' => '_balnk']);
//                    return $str;
//                } else {
//                    return '';
//                }
//            }],
//            ['attribute' => 'tking_id', 'format' => 'raw', 'value' => function($model){
//                $str = Html::a('摩天轮', \Yii::$app->params['tkingHost'] . '/content/' . $model->tking_id, ['target' => '_balnk']);
//                return $str;
//            }],
//            ['attribute' => 'youpiao_id', 'format' => 'raw', 'value' => function($model){
//                $str = Html::a('有票', \Yii::$app->params['youpiaoHost'] . '/t_' . $model->youpiao_id, ['target' => '_balnk']);
//                return $str;
//            }],
            'start_time',
            'end_time',
//            'is_index',
            ['attribute' => 'note', 'format' => 'raw', 'value' => function($model){
                $str = "<i style='color: #ff383c; font-size: 12px;'>" . $model->note . "</i>";
                return $str;
            }],
            ['header' => '操作', 'class' => 'yii\grid\ActionColumn', 'template' => '{update}'],
        ],
    ]) ?>
</div>
