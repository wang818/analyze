<?php
$this->title = "票档售价列表";
$this->params['breadcrumbs'][] = $this->title;
?>
<h3><?=$model->event->name?></h3>
<?php
    if (count($list_arr) > 0){
        foreach ($list_arr as $item) {
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
                        <tr class="active">
                            <td><?=$source?></td>
                            <?php
                            foreach ($item['price_list'] as $price){
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