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

    <?php if ($dataProviderDraft->totalCount > 0) { ?>
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
                    'template' => '{continue} {delete}',
                    'buttons' => [
                        'continue' => function ($url, $model, $key) {
                            return Html::a(Yii::t('app', 'Continue'), ['draft', 'transaction_id' => $model->transaction_id], ['class' => 'btn btn-outline-warning btn-xs']);
                        },
                        'delete' => function ($url, $model, $key) {
                            if (Utils::belongsToCompany($model->company_id)) {
                                $question = Yii::t('app', "Are you sure you want to delete the transaction {code}-{name}?", ['code' => $model->document->code, 'name' => $model->num_transaction]);
                                return Html::a(Yii::t('app', 'Delete'), ['delete-draft', 'transaction_id' => $model->transaction_id], [
                                    'class' => "btn btn-outline-danger btn-xs",
                                    'data' => [
                                        'confirm' => $question,
                                        'method' => 'post',
                                    ],
                                ]);
                            }
                        },
                    ]
                ],
            ],
        ]); ?>

        <h2><?= Html::encode(Yii::t('app', 'Saved Transactions')) ?></h2>

    <?php } ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
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
                'attribute' => Yii::t('app', 'Status'),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->getFullStatus();
            },
            ],
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