<?php

use app\models\Constants;
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_LIST_USERS_PENDING);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE), 'url' => ['companies/']];
$this->params['breadcrumbs'][] = ['label' => $company->name, 'url' => ['companies/view', 'company_id' => $company->company_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_LIST_USERS_TITLE), 'url' => ['companies/list-users', 'company_id' => $company->company_id]];
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
                'attribute' => Yii::t(TextConstants::APPLICATION, TextConstants::APPLICATION_MODEL_COMMENT),
                'format' => 'raw',
                'value' => function ($model) {
                        return $model->comment_user;
                    },
            ],
            [
                'attribute' => Yii::t(TextConstants::APPLICATION, TextConstants::APPLICATION_MODEL_SENT_AT),
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
                            return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_VIEW), ['view', 'application_id' => $model->application_id, 'is_company' => true], ['class' => 'btn btn-outline-primary btn-xs']);
                        },
                    'approve' => function ($url, $model, $key) {
                            if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                                return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_APPROVE), ['approve', 'application_id' => $model->application_id], [
                                    'class' => 'btn btn-outline-success btn-xs',
                                    'data' => [
                                        'confirm' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_CONFIRMATION_APPROVE, ['name' => $model->user->name]),
                                        'method' => 'post',
                                    ],
                                ]);
                            }
                        },
                    'deny' => function ($url, $model, $key) {
                            if (Utils::isOwnerOrSupervisorOfCompany($model->company_id)) {
                                return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_DENY), ['deny', 'application_id' => $model->application_id], ['class' => 'btn btn-outline-danger btn-xs']);
                            }
                        },
                ]
            ],
        ],
    ]); ?>

</div>