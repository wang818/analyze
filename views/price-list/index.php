<?php
$this->title = "票档售价列表";
$this->params['breadcrumbs'][] = $this->title;
?>
<h3><?=$event_info->name?></h3>
<?php
    if (count($last_arr) > 0){
        foreach ($last_arr as $item) {
            ?>
            <div class="panel panel-inverse" data-sortable-id="table-basic-6">
                <div class="panel-heading">
                    <h4 class="panel-title"><?=$item['name']?></h4>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>1张</th>
                            <th>2张</th>
                            <th>3张</th>
                            <th>4张</th>
                            <th>5张</th>
                            <th>6张</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach ($item['price_list'] as $priceList){
                                ?>
                                <tr class="active">
                                    <td><?php
                                        if ($priceList['source'] == Yii::$app->params['platform']['ypiao']) echo '有票';
                                        if ($priceList['source'] == Yii::$app->params['platform']['piaoniu']) echo '票牛';
                                        if ($priceList['source'] == Yii::$app->params['platform']['tking']) echo '摩天轮';
                                        ?></td>
                                    <?php
                                    foreach ($priceList['price_list'] as $price){
                                        if (isset($price['price'])){
                                            $priceOne = $price['price'];
                                        } else {
                                            $priceOne = 0;
                                        }
                                        ?>
                                        <td><?=$priceOne?></td>
                                        <?php
                                    }

                                    ?>
                                </tr>
                                <?php
                            }
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <?php

        }
    }
?>

<style>
    .panel-heading{
        color: #fff;
        background-color: #000000;
    }
</style>