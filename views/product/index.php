<?php

use app\models\Constants;
use app\models\entities\Product;
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t(TextConstants::INDEX, TextConstants::INDEX_PRODUCTS_TITLE);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Utils::isOwnerOrSupervisorOfCompany($companyId)) {
            echo Html::a(Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_BUTTON_CREATE), ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => Constants::NUM,
                'format' => 'raw',
                'value' => function ($model) {
                return $model->product_id;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CODE),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->code;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_NAME),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->name;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_DESCRIPTION),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->description;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_HAS_EXISTENCES),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->getFullExistences();
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
                'template' => '{view} {update} {activate}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                    return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_VIEW), ['view', 'product_id' => $model->product_id], ['class' => 'btn btn-outline-info btn-xs']);
                },
                    'update' => function ($url, $model, $key) {
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                        return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE), ['update', 'product_id' => $model->product_id], ['class' => 'btn btn-outline-secondary btn-xs']);
                    }
                },
                    'activate' => function ($url, $model, $key) {
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                        if ($model->isActive()) {
                            $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_DEACTIVATE);
                            $class = "btn btn-outline-danger btn-xs";
                            $question = Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_INDEX_CONFIRMATION_DEACTIVATE, ['code' => $model->code, 'name' => $model->name]);
                        } elseif ($model->isInactive()) {
                            $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_ACTIVATE);
                            $class = "btn btn-outline-warning btn-xs";
                            $question = Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_INDEX_CONFIRMATION_ACTIVATE, ['code' => $model->code, 'name' => $model->name]);
                        }
                        return Html::a($label, ['activate', 'product_id' => $model->product_id], [
                            'class' => $class,
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


</div>