<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 2018/1/4
 * Time: 下午9:45
 */

$this->title = '创建项目';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-create">
    <?= $this->render('_form', [
        'model' => $model
    ])?>
</div>
