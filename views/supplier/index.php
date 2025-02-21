<?php

use app\models\Constants;
use app\models\entities\City;
use app\models\entities\Supplier;
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t(TextConstants::INDEX, TextConstants::INDEX_SUPPLIERS_TITLE);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Utils::isOwnerOrSupervisorOfCompany($companyId)) {
            echo Html::a(Yii::t(TextConstants::SUPPLIER, TextConstants::SUPPLIER_BUTTON_CREATE), ['create'], ['class' => 'btn btn-success']);
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
                return $model->supplier_id;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_CODE),
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
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_EMAIL),
                'format' => 'email',
                'value' => function ($model) {
                return $model->email;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_PHONE),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->phone;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_ADDRESS),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->address;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::CITY, TextConstants::CITY_MODEL_ID),
                'format' => 'raw',
                'value' => function ($model) {
                $city = City::findOne(['city_id' => $model->city]);
                return $city->name;
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
                    return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_VIEW), ['view', 'supplier_id' => $model->supplier_id], ['class' => 'btn btn-outline-info btn-xs']);
                },
                    'update' => function ($url, $model, $key) {
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                        return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE), ['update', 'supplier_id' => $model->supplier_id], ['class' => 'btn btn-outline-secondary btn-xs']);
                    }
                },
                    'activate' => function ($url, $model, $key) {
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                        if ($model->isActive()) {
                            $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_DEACTIVATE);
                            $class = "btn btn-outline-danger btn-xs";
                            $question = Yii::t(TextConstants::SUPPLIER, TextConstants::SUPPLIER_INDEX_CONFIRMATION_DEACTIVATE, ['code' => $model->code, 'name' => $model->name]);
                        } elseif ($model->isInactive()) {
                            $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_ACTIVATE);
                            $class = "btn btn-outline-warning btn-xs";
                            $question = Yii::t(TextConstants::SUPPLIER, TextConstants::SUPPLIER_INDEX_CONFIRMATION_ACTIVATE, ['code' => $model->code, 'name' => $model->name]);
                        }
                        return Html::a($label, ['activate', 'supplier_id' => $model->supplier_id], [
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