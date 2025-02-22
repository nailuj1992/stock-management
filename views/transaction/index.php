<?php

use app\models\Constants;
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $dataProviderDraft */

$this->title = Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_TITLE_INDEX);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Utils::belongsToCompany($companyId)) { ?>
            <?= Html::a(Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_BUTTON_CREATE), ['create'], ['class' => 'btn btn-success']) ?>
        <?php } ?>
    </p>

    <?php if ($dataProviderDraft->totalCount > 0) { ?>
        <h2><?= Html::encode(Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_SUBTITLE_DRAFTS)) ?></h2>

        <?= GridView::widget([
            'dataProvider' => $dataProviderDraft,
            'columns' => [
                [
                    'attribute' => Constants::NUM,
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->document->code . ' - ' . $model->num_transaction;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_CREATION_DATE),
                    'format' => 'date',
                    'value' => function ($model) {
                                return $model->creation_date;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_EXPIRATION_DATE),
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
                                    return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_CONTINUE), ['draft', 'transaction_id' => $model->transaction_id], ['class' => 'btn btn-outline-warning btn-xs']);
                                },
                        'delete' => function ($url, $model, $key) {
                                    if (Utils::belongsToCompany($model->company_id)) {
                                        $question = Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_INDEX_CONFIRMATION_DELETE, ['code' => $model->document->code, 'name' => $model->num_transaction]);
                                        return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_DELETE), ['delete-draft', 'transaction_id' => $model->transaction_id], [
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

        <h2><?= Html::encode(Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_SUBTITLE_SAVED)) ?></h2>

    <?php } ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => Constants::NUM,
                'format' => 'raw',
                'value' => function ($model) {
                return $model->document->code . ' - ' . $model->num_transaction;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_CREATION_DATE),
                'format' => 'date',
                'value' => function ($model) {
                return $model->creation_date;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_EXPIRATION_DATE),
                'format' => 'date',
                'value' => function ($model) {
                return $model->expiration_date;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
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
                    return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_VIEW), ['view', 'transaction_id' => $model->transaction_id], ['class' => 'btn btn-outline-info btn-xs']);
                },
                ]
            ],
        ],
    ]); ?>

</div>