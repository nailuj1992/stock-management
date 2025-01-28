<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'List applications');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['companies/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applications-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => Yii::t('app', 'Code'),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->company->code;
                    }
            ],
            [
                'attribute' => Yii::t('app', 'Name'),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->company->name;
                    }
            ],
            [
                'attribute' => Yii::t('app', 'Phone'),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->company->phone;
                    }
            ],
            [
                'attribute' => Yii::t('app', 'Status'),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->getFullStatus();
                    },
            ],
            [
                'attribute' => Yii::t('app', 'Sent at'),
                'format' => ['date', 'php:d/m/Y H:i:s'],
                'value' => function ($model) {
                        return $model->created_at;
                    },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                            return Html::a(Yii::t('app', 'View'), ['view', 'application_id' => $model->application_id, 'show_list' => true], ['class' => 'btn btn-outline-primary btn-xs']);
                        },
                ]
            ],
        ],
    ]); ?>

</div>