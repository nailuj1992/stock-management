<?php

use app\models\Constants;
use app\models\Utils;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Pending Applications');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['companies/']];
$this->params['breadcrumbs'][] = ['label' => $company->name, 'url' => ['companies/view', 'company_id' => $company->company_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Current Users'), 'url' => ['companies/list-users', 'company_id' => $company->company_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pending-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'user.name',
            'user.email:email',
            'user.phone',
            [
                'attribute' => Yii::t('app', 'Comment'),
                'format' => Yii::t('app', 'raw'),
                'value' => function ($model) {
                        return $model->comment_user;
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
                'template' => '{view} {approve} {deny}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                            return Html::a(Yii::t('app', 'View'), ['view', 'application_id' => $model->application_id, 'is_company' => true], ['class' => 'btn btn-outline-primary btn-xs']);
                        },
                    'approve' => function ($url, $model, $key) {
                            if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                                return Html::a(Yii::t('app', 'Approve'), ['approve', 'application_id' => $model->application_id], [
                                    'class' => 'btn btn-outline-success btn-xs',
                                    'data' => [
                                        'confirm' => Yii::t('app', "Are you sure you want to approve this application for the user {name}?", ['name' => $model->user->name]),
                                        'method' => 'post',
                                    ],
                                ]);
                            }
                        },
                    'deny' => function ($url, $model, $key) {
                            if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                                return Html::a(Yii::t('app', 'Deny'), ['deny', 'application_id' => $model->application_id], ['class' => 'btn btn-outline-danger btn-xs']);
                            }
                        },
                ]
            ],
        ],
    ]); ?>

</div>