<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/1/29
 * Time: 下午7:38
 */

$this->title = '修改订单';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-update">
    <?= $this->render('_form', [
        'model' => $model
    ])?>
</div>
