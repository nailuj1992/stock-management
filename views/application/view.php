<?php

use app\models\entities\City;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\entities\ApplicationCompany $model */

$this->title = !$isCompany ? Yii::t('app', 'View Application for {name}', ['name' => $model->company->name]) : Yii::t('app', 'View Application by {name}', ['name' => $model->user->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['companies/']];
if (!$isCompany) {
    if (!$showList) {
        $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Applications'), 'url' => ['index', 'company_id' => $model->company_id]];
    } else {
        $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'List applications'), 'url' => ['list']];
    }
} else {
    $this->params['breadcrumbs'][] = ['label' => $model->company->name, 'url' => ['companies/view', 'company_id' => $model->company_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Current Users'), 'url' => ['companies/list-users', 'company_id' => $model->company_id]];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pending Applications'), 'url' => ['list-pending', 'company_id' => $model->company_id]];
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
                    'label' => Yii::t('app', 'City'),
                    'value' => $city->name,
                ],
                [
                    'label' => Yii::t('app', 'State'),
                    'value' => $state->name,
                ],
                [
                    'label' => Yii::t('app', 'Country'),
                    'value' => $country->name,
                ],
                'comment_user',
                [
                    'label' => Yii::t('app', 'Status'),
                    'value' => $model->getFullStatus(),
                ],
                [
                    'label' => Yii::t('app', 'Feedback'),
                    'value' => isset($model->comment_company) && strlen($model->comment_user) > 0 ? $model->comment_user : '',
                ],
            ],
        ]);
    } else {
        ?>
        <p>
            <?= Utils::isOwnerOrSupervisorOfCompany($model->company_id) ? Html::a(Yii::t('app', 'Approve'), ['approve', 'application_id' => $model->application_id], [
                'class' => 'btn btn-success btn-xs',
                'data' => [
                    'confirm' => Yii::t('app', "Are you sure you want to approve this application for the user {name}?", ['name' => $model->user->name]),
                    'method' => 'post',
                ],
            ]) : '' ?>
            <?= Utils::isOwnerOrSupervisorOfCompany($model->company_id) ? Html::a(Yii::t('app', 'Deny'), ['deny', 'application_id' => $model->application_id], ['class' => 'btn btn-danger btn-xs']) : '' ?>
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
                    'label' => Yii::t('app', 'City'),
                    'value' => $city->name,
                ],
                [
                    'label' => Yii::t('app', 'State'),
                    'value' => $state->name,
                ],
                [
                    'label' => Yii::t('app', 'Country'),
                    'value' => $country->name,
                ],
                [
                    'label' => Yii::t('app', 'Comment'),
                    'value' => $model->comment_user,
                ],
                [
                    'label' => Yii::t('app', 'Status'),
                    'value' => $model->getFullStatus(),
                ],
                [
                    'label' => Yii::t('app', 'Feedback'),
                    'value' => isset($model->comment_company) && strlen($model->comment_user) > 0 ? $model->comment_user : '',
                ],
            ],
        ]);
    }
    ?>

</div>