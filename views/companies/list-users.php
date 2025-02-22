<?php

use app\models\Constants;
use app\models\entities\ApplicationCompany;
use app\models\entities\UserCompany;
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_LIST_USERS_TITLE);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $company->name, 'url' => ['view', 'company_id' => $company->company_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-users-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_BUTTON_CREATE_USER), ['create-user', 'company_id' => $company->company_id], ['class' => 'btn btn-success']) ?>
        <?php
        if (count(ApplicationCompany::getPendingApplicationsForCompany($company->company_id)) > 0) {
            echo Html::a(Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_LIST_USERS_PENDING), ['application/list-pending', 'company_id' => $company->company_id], ['class' => 'btn btn-warning']);
        }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'user.name',
            'user.email:email',
            'user.phone',
            [
                'attribute' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_POSITION),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->getFullRole();
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_USER_STATUS),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->user->getFullStatus();
            },
            ],
            [
                'attribute' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_STATUS_IN_COMPANY),
                'format' => 'raw',
                'value' => function ($model) {
                return $model->getFullStatus();
            },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view-user} {change-rol} {activate}',
                'buttons' => [
                    'view-user' => function ($url, $model, $key) {
                    return Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_VIEW), ['view-user', 'user_id' => $model->user_id, 'company_id' => $model->company_id], ['class' => 'btn btn-outline-primary btn-xs']);
                },
                    'change-rol' => function ($url, $model, $key) {
                    if ($model->isMember()) {
                        $label = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_PROMOTE);
                        $class = "btn btn-outline-success btn-xs";
                        $question = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_CONFIRMATION_PROMOTE, ['name' => $model->user->name, 'role' => Yii::t(TextConstants::ROLE, TextConstants::ROLE_SUPERVISOR)]);
                    } elseif ($model->isSupervisor()) {
                        $label = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_DEMOTE);
                        $class = "btn btn-outline-warning btn-xs";
                        $question = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_CONFIRMATION_DEMOTE, ['name' => $model->user->name, 'role' => Yii::t(TextConstants::ROLE, TextConstants::ROLE_MEMBER)]);
                    }
                    if (Utils::isOwnerOfCompany($model->company_id) && !$model->isOwner() && $model->user->isActive() && $model->isActive()) {
                        return Html::a($label, ['change-role', 'user_id' => $model->user_id, 'company_id' => $model->company_id], [
                            'class' => $class,
                            'data' => [
                                'confirm' => $question,
                                'method' => 'post',
                            ],
                        ]);
                    }
                },
                    'activate' => function ($url, $model, $key) {
                    if ($model->isActive()) {
                        $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_DEACTIVATE);
                        $question = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_CONFIRMATION_DEACTIVATE, ['name' => $model->user->name]);
                    } elseif ($model->isInactive()) {
                        $label = Yii::t(TextConstants::APP, TextConstants::BUTTON_ACTIVATE);
                        $question = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_CONFIRMATION_ACTIVATE, ['name' => $model->user->name]);
                    }
                    $user = UserCompany::findUserCompanyRow(Yii::$app->user->identity->user_id, $model->company_id);
                    if (Utils::isOwnerOrSupervisorOfCompany($model->company_id) && $user->user_id != $model->user_id && !$model->isOwner() && !($user->isSupervisor() && $model->isSupervisor())) {
                        return Html::a($label, ['activate', 'user_id' => $model->user_id, 'company_id' => $model->company_id], [
                            'class' => 'btn btn-outline-danger btn-xs',
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