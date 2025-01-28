<?php

use app\models\entities\City;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\entities\Warehouse;
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
                'attribute' => Yii::t('app', 'Code'),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->code;
            },
            ],
            [
                'attribute' => Yii::t('app', 'Name'),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->name;
            },
            ],
            [
                'attribute' => Yii::t('app', 'Address'),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->address;
            },
            ],
            [
                'attribute' => Yii::t('app', 'City'),
                'format' => 'raw',
                'value' => function ($model) {
                $city = City::findOne(['city_id' => $model->city]);
                return $city->name;
            },
            ],
            [
                'attribute' => Yii::t('app', 'State'),
                'format' => 'raw',
                'value' => function ($model) {
                $city = City::findOne(['city_id' => $model->city]);
                $state = State::findOne(['state_id' => $city->state_id]);
                return $state->name;
            },
            ],
            [
                'attribute' => Yii::t('app', 'Country'),
                'format' => 'raw',
                'value' => function ($model) {
                $city = City::findOne(['city_id' => $model->city]);
                $state = State::findOne(['state_id' => $city->state_id]);
                $country = Country::findOne(['country_id' => $state->country_id]);
                return $country->name;
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
                'template' => '{view} {update} {activate}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                    return Html::a(Yii::t('app', 'View'), ['view', 'warehouse_id' => $model->warehouse_id], ['class' => 'btn btn-outline-info btn-xs']);
                },
                    'update' => function ($url, $model, $key) {
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                        return Html::a(Yii::t('app', 'Update'), ['update', 'warehouse_id' => $model->warehouse_id], ['class' => 'btn btn-outline-secondary btn-xs']);
                    }
                },
                    'activate' => function ($url, $model, $key) {
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                        if ($model->isActive()) {
                            $label = Yii::t('app', 'Deactivate');
                            $class = "btn btn-outline-danger btn-xs";
                            $question = Yii::t('app', "Are you sure you want to deactivate the warehouse {code}-{name}?", ['code' => $model->code, 'name' => $model->name]);
                        } elseif ($model->isInactive()) {
                            $label = Yii::t('app', 'Activate');
                            $class = "btn btn-outline-warning btn-xs";
                            $question = Yii::t('app', "Are you sure you want to activate the warehouse {code}-{name}?", ['code' => $model->code, 'name' => $model->name]);
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