<?php

use app\models\entities\City;
use app\models\entities\Supplier;
use app\models\Utils;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Suppliers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Utils::isOwnerOrSupervisorOfCompany($companyId)) {
            echo Html::a(Yii::t('app', 'Create Supplier'), ['create'], ['class' => 'btn btn-success']);
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
                return $model->supplier_id;
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
                'attribute' => Yii::t('app', 'Email'),
                'format' => 'email',
                'value' => function ($model) {
                return $model->email;
            },
            ],
            [
                'attribute' => Yii::t('app', 'Phone'),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->phone;
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
                    return Html::a(Yii::t('app', 'View'), ['view', 'supplier_id' => $model->supplier_id], ['class' => 'btn btn-outline-info btn-xs']);
                },
                    'update' => function ($url, $model, $key) {
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                        return Html::a(Yii::t('app', 'Update'), ['update', 'supplier_id' => $model->supplier_id], ['class' => 'btn btn-outline-secondary btn-xs']);
                    }
                },
                    'activate' => function ($url, $model, $key) {
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                        if ($model->isActive()) {
                            $label = Yii::t('app', 'Deactivate');
                            $class = "btn btn-outline-danger btn-xs";
                            $question = Yii::t('app', "Are you sure you want to deactivate the supplier {code} - {name}?", ['code' => $model->code, 'name' => $model->name]);
                        } elseif ($model->isInactive()) {
                            $label = Yii::t('app', 'Activate');
                            $class = "btn btn-outline-warning btn-xs";
                            $question = Yii::t('app', "Are you sure you want to activate the supplier {code} - {name}?", ['code' => $model->code, 'name' => $model->name]);
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