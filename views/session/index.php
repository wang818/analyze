<?php
use yii\helpers\Html;

$this->title = '场次列表';
$this->params['breadcrumbs'][] = $this->title;
$js = <<<JS
    var compareArr = [];
    $(".add_compare").on('click', function (e) {
        e.preventDefault();
        if($(this).hasClass('fa-plus')){
            $(this).removeClass('fa-plus').addClass('fa-minus');
            // +
            var obj = $(this).parent();
            $("#compare").append(obj);
            compareArr.push($(this).data('id'));
        } else {
            $(this).removeClass('fa-minus').addClass('fa-plus');
            // - 
            var obj = $(this).parent();
            $("#session_list").append(obj);
            var i = compareArr.indexOf($(this).data('id'))
            compareArr.splice(i, 1);
        }     
        console.log(compareArr);
        // 处理链接
        if (compareArr.length > 0){
            var str = compareArr.join(',');
            var a = $("<a></a>");
            a.attr('href', '?r=price-list/index&session_id=' + str)
            .addClass('btn btn-lg btn-info')
            .text('对比');
            $('#compare_btn').text('').append(a);
        } else {
            $('#compare_btn').text('');
        }
    })
JS;
$this->registerJS($js);
?>

<div id="session_list">
    <?php
    foreach ($model as $session){
        switch ($session['source']){
            case Yii::$app->params['platform']['piaoniu']:
                ?>
                <a href="?r=price-list/index&session_id=<?=$session['id']?>" class="btn btn-danger btn-lg btn-block"><?=$session['name']. '(票牛)'?> <i class="fa fa-plus add_compare" data-id="<?=$session['id']?>"></i></a>
                <?php
//                echo Html::a($session['name']. '(票牛)', '', ['class' => 'btn btn-danger btn-lg btn-block']);
                break;

            case Yii::$app->params['platform']['ypiao']:
                ?>
                <a href="?r=price-list/index&session_id=<?=$session['id']?>" class="btn btn-warning btn-lg btn-block"><?=$session['name']. '(有票)'?><i class="fa fa-plus add_compare" data-id="<?=$session['id']?>"></i></a>
                <?php
//                echo Html::a($session['name']. '(有票)', '', ['class' => 'btn btn-warning btn-lg btn-block']);
                break;
            case Yii::$app->params['platform']['tking']:
                ?>
                <a href="?r=price-list/index&session_id=<?=$session['id']?>" class="btn btn-primary btn-lg btn-block"><?=$session['name']. '(摩天轮)'?><i class="fa fa-plus add_compare" data-id="<?=$session['id']?>"></i></a>
                <?php
//                echo Html::a($session['name']. '(摩天轮)', '', ['class' => 'btn btn-primary btn-lg btn-block']);
                break;
            default:
                ?>
                <a href="?r=price-list/index&session_id=<?=$session['id']?>" class="btn btn-link btn-lg btn-block"><?=$session['name']?><i class="fa fa-plus"></i></a>
                <?php
//                echo Html::a($session['name']. '(。。。)', '', ['class' => 'btn btn-link btn-lg btn-block']);
                break;
        }
    }
    ?>
</div>

<h3>对比列表</h3>
<div id="compare"></div>
<div id="compare_btn"></div>


<style>
    .fa{
        float: right;
    }
    #compare_btn{
        margin-top: 5px;
    }
</style>
