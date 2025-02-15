<?php

use app\models\Utils;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $dataProviderDraft */

$this->title = Yii::t('app', 'View Transactions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Utils::belongsToCompany($companyId)) {
            echo Html::a(Yii::t('app', 'Create Transaction'), ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>

    <h2><?= Html::encode(Yii::t('app', 'Drafts')) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProviderDraft,
        'columns' => [
            [
                'attribute' => '#',
                'format' => 'raw',
                'value' => function ($model) {
                return $model->document->code . ' - ' . $model->num_transaction;
            },
            ],
            [
                'attribute' => Yii::t('app', 'Creation Date'),
                'format' => 'date',
                'value' => function ($model) {
                return $model->creation_date;
            },
            ],
            [
                'attribute' => Yii::t('app', 'Expiration Date'),
                'format' => 'date',
                'value' => function ($model) {
                return $model->expiration_date;
            },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{continue}',
                'buttons' => [
                    'continue' => function ($url, $model, $key) {
                    return Html::a(Yii::t('app', 'Continue'), ['draft', 'transaction_id' => $model->transaction_id], ['class' => 'btn btn-outline-warning btn-xs']);
                },
                ]
            ],
        ],
    ]); ?>

    <h2><?= Html::encode(Yii::t('app', 'Saved Transactions')) ?></h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => '#',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->num_transaction;
                },
            ],
            // [
            //     'attribute' => Yii::t('app', 'Code'),
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //     return $model->code;
            // },
            // ],
            // [
            //     'attribute' => Yii::t('app', 'Name'),
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //     return $model->name;
            // },
            // ],
            // [
            //     'attribute' => Yii::t('app', 'Intended For'),
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //     return $model->getFullAction();
            // },
            // ],
            // [
            //     'attribute' => Yii::t('app', 'Apply For'),
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //     return $model->getFullApply();
            // },
            // ],
            // [
            //     'attribute' => Yii::t('app', 'Status'),
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //     return $model->getFullStatus();
            // },
            // ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(Yii::t('app', 'View'), ['view', 'transaction_id' => $model->transaction_id], ['class' => 'btn btn-outline-info btn-xs']);
                    },
                ]
            ],
        ],
    ]); ?>

</div>