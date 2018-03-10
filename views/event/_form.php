<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/1/4
 * Time: 下午9:46
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ArrayHelper;
use dosamigos\datetimepicker\DateTimePicker;
?>
<div class="event_form">
    <?php $form = ActiveForm::begin(); ?>
    <?php
    if (!$model->is_index){
        $model->is_index = 0;
    }
    echo $form->field($model, 'name')->textInput(['maxlength' => 200]);
    echo $form->field($model, 'damai_id')->textInput(['maxlength' => 200]);
    echo $form->field($model, 'yongle_id')->textInput(['maxlength' => 200]);
    echo $form->field($model, 'piaoniu_id')->textInput(['maxlength' => 200]);
    echo $form->field($model, 'xishiqu_id')->textInput(['maxlength' => 200]);
    echo $form->field($model, 'tking_id')->textInput(['maxlength' => 200]);
    echo $form->field($model, 'youpiao_id')->textInput(['maxlength' => 200]);
    echo $form->field($model, 'start_time')->widget(DateTimePicker::className(), [
        'language' => 'zh-CN',
        'size' => 'ms',
        'template' => '{input}{addon}',
        'pickButtonIcon' => 'glyphicon glyphicon-time',
        'inline' => false,
        'clientOptions' => [
            'minView' => 0,
            'maxView' => 1,
            'autoclose' => true,
//            'linkFormat' => 'YYYY-MM-DD HH:ii P', // if inline = true
             'format' => 'yyyy-mm-dd hh:ii', // if inline = false
            'todayBtn' => true
        ]
    ]);
    echo $form->field($model, 'end_time')->widget(DateTimePicker::className(), [
        'language' => 'zh-CN',
        'size' => 'ms',
        'template' => '{input}{addon}',
        'pickButtonIcon' => 'glyphicon glyphicon-time',
        'inline' => false,
        'clientOptions' => [
            'minView' => 0,
            'maxView' => 1,
            'autoclose' => true,
//            'linkFormat' => 'YYYY-MM-DD HH:ii P', // if inline = true
            'format' => 'yyyy-mm-dd hh:ii', // if inline = false
            'todayBtn' => true
        ]
    ]);
    echo $form->field($model, 'is_index')->radioList(['0' => '不上首页', '1' => '上首页']);
    echo $form->field($model, 'note')->textInput(['maxlength' => 200]);
    ?>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
</div>






<?php ActiveForm::end(); ?>