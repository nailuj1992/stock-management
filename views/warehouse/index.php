<?php

use app\models\entities\City;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\entities\Warehouse;
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Warehouses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="warehouse-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Utils::isOwnerOrSupervisorOfCompany($companyId)) {
            echo Html::a(Yii::t('app', 'Create Warehouse'), ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => '#',
                'format' => 'raw',
                'value' => function ($model) {
                return $model->warehouse_id;
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
                'attribute' => Yii::t(TextConstants::STATE, TextConstants::STATE_MODEL_ID),
                'format' => 'raw',
                'value' => function ($model) {
                $city = City::findOne(['city_id' => $model->city]);
                $state = State::findOne(['state_id' => $city->state_id]);
                return $state->name;
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
                'format' => 'raw',
                'value' => function ($model) {
                $city = City::findOne(['city_id' => $model->city]);
                $state = State::findOne(['state_id' => $city->state_id]);
                $country = Country::findOne(['country_id' => $state->country_id]);
                return $country->name;
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
                    return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_VIEW), ['view', 'warehouse_id' => $model->warehouse_id], ['class' => 'btn btn-outline-info btn-xs']);
                },
                    'update' => function ($url, $model, $key) {
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                        return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE), ['update', 'warehouse_id' => $model->warehouse_id], ['class' => 'btn btn-outline-secondary btn-xs']);
                    }
                },
                    'activate' => function ($url, $model, $key) {
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                        if ($model->isActive()) {
                            $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_DEACTIVATE);
                            $class = "btn btn-outline-danger btn-xs";
                            $question = Yii::t(TextConstants::WAREHOUSE, TextConstants::WAREHOUSE_INDEX_CONFIRMATION_DEACTIVATE, ['code' => $model->code, 'name' => $model->name]);
                        } elseif ($model->isInactive()) {
                            $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_ACTIVATE);
                            $class = "btn btn-outline-warning btn-xs";
                            $question = Yii::t(TextConstants::WAREHOUSE, TextConstants::WAREHOUSE_INDEX_CONFIRMATION_ACTIVATE, ['code' => $model->code, 'name' => $model->name]);
                        }
                        return Html::a($label, ['activate', 'warehouse_id' => $model->warehouse_id], [
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