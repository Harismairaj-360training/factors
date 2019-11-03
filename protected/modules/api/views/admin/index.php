<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\widgets\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo '<strong>Manage</strong> Api Users'; ?></div>
    <div class="panel-body">
    <?= \humhub\modules\api\widgets\ApiUserMenu::widget(); ?>
    <p />

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => ['style' => 'width:40px;'],
                'format' => 'raw',
                'value' => function($data) {
            return $data->id;
        },
            ],
            'id',
            'client',
            'api_key',
            'active',

            [
                'header' => 'Actions', 
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                        'view' => function($url, $model) {
                            return Html::a('<i class="fa fa-eye"></i>',Url::toRoute(['view', 'id' => $model->id]), ['class' => 'btn btn-primary btn-xs tt']);
                        },
                        'update' => function($url, $model) {
                            return Html::a('<i class="fa fa-pencil"></i>', Url::toRoute(['update', 'id' => $model->id]), ['class' => 'btn btn-primary btn-xs tt']);
                        },
                        'delete' => function($url, $model) {
                            return Html::a('<i class="fa fa-times"></i>', Url::toRoute(['delete', 'id' => $model->id]), ['class' => 'btn btn-danger btn-xs tt']);
                        }
                ]
            ],
        ],
    ]); ?>
    </div>
</div>
