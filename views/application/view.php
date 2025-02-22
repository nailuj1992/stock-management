<?php

use app\models\entities\City;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\entities\ApplicationCompany $model */

$this->title = !$isCompany ? Yii::t(TextConstants::APPLICATION, TextConstants::COMPANY_VIEW_APPLICATION_COMPANY_TITLE, ['name' => $model->company->name]) : Yii::t(TextConstants::APPLICATION, TextConstants::COMPANY_VIEW_APPLICATION_USER_TITLE, ['name' => $model->user->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE), 'url' => ['companies/']];
if (!$isCompany) {
    if (!$showList) {
        $this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::APPLICATION, TextConstants::COMPANY_APPLICATIONS_TITLE), 'url' => ['index', 'company_id' => $model->company_id]];
    } else {
        $this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_BUTTON_SHOW_APPLICATIONS), 'url' => ['list']];
    }
} else {
    $this->params['breadcrumbs'][] = ['label' => $model->company->name, 'url' => ['companies/view', 'company_id' => $model->company_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_LIST_USERS_TITLE), 'url' => ['companies/list-users', 'company_id' => $model->company_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_LIST_USERS_PENDING), 'url' => ['list-pending', 'company_id' => $model->company_id]];
}
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="application-company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $city = City::findOne(['city_id' => !$isCompany ? $model->company->city : $model->user->city]);
    $state = State::findOne(['state_id' => $city->state_id]);
    $country = Country::findOne(['country_id' => $state->country_id]);
    ?>

    <?php
    if (!$isCompany) {
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'company.code',
                'company.name',
                'company.phone',
                'company.address',
                [
                    'label' => Yii::t(TextConstants::CITY, TextConstants::CITY_MODEL_ID),
                    'value' => $city->name,
                ],
                [
                    'label' => Yii::t(TextConstants::STATE, TextConstants::STATE_MODEL_ID),
                    'value' => $state->name,
                ],
                [
                    'label' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
                    'value' => $country->name,
                ],
                'comment_user',
                [
                    'label' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
                    'value' => $model->getFullStatus(),
                ],
                [
                    'label' => Yii::t(TextConstants::APPLICATION, TextConstants::APPLICATION_MODEL_FEEDBACK),
                    'value' => isset($model->comment_company) && strlen($model->comment_user) > 0 ? $model->comment_user : '',
                ],
            ],
        ]);
    } else {
        ?>
        <p>
            <?= Utils::isOwnerOrSupervisorOfCompany($model->company_id) ? Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_APPROVE), ['approve', 'application_id' => $model->application_id], [
                'class' => 'btn btn-success btn-xs',
                'data' => [
                    'confirm' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_CONFIRMATION_APPROVE, ['name' => $model->user->name]),
                    'method' => 'post',
                ],
            ]) : '' ?>
            <?= Utils::isOwnerOrSupervisorOfCompany($model->company_id) ? Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_DENY), ['deny', 'application_id' => $model->application_id], ['class' => 'btn btn-danger btn-xs']) : '' ?>
        </p>
        <?php
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'user.name',
                'user.email:email',
                'user.phone',
                'user.address',
                [
                    'label' => Yii::t(TextConstants::CITY, TextConstants::CITY_MODEL_ID),
                    'value' => $city->name,
                ],
                [
                    'label' => Yii::t(TextConstants::STATE, TextConstants::STATE_MODEL_ID),
                    'value' => $state->name,
                ],
                [
                    'label' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
                    'value' => $country->name,
                ],
                [
                    'label' => Yii::t(TextConstants::APPLICATION, TextConstants::APPLICATION_MODEL_COMMENT),
                    'value' => $model->comment_user,
                ],
                [
                    'label' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
                    'value' => $model->getFullStatus(),
                ],
                [
                    'label' => Yii::t(TextConstants::APPLICATION, TextConstants::APPLICATION_MODEL_FEEDBACK),
                    'value' => isset($model->comment_company) && strlen($model->comment_user) > 0 ? $model->comment_user : '',
                ],
            ],
        ]);
    }
    ?>

</div>