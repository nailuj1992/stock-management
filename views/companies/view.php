<?php

use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\entities\City;
use app\models\entities\State;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\entities\UserCompany $model */

$this->title = Yii::t('app', 'Information for company: {name}', ['name' => $model->company->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->company->name;
\yii\web\YiiAsset::register($this);
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Utils::isOwnerOfCompany($model->company->company_id) ? Html::a(Yii::t('app', 'Update'), ['update', 'company_id' => $model->company->company_id], ['class' => 'btn btn-primary']) : '' ?>
        <?= Utils::isOwnerOrSupervisorOfCompany($model->company->company_id) ? Html::a(Yii::t('app', 'List Users'), ['list-users', 'company_id' => $model->company->company_id], ['class' => 'btn btn-outline-info']) : '' ?>
        <?= Utils::isOwnerOrSupervisorOfCompany($model->company->company_id) ? Html::a(Yii::t('app', 'Create User'), ['create-user', 'company_id' => $model->company->company_id], ['class' => 'btn btn-outline-success']) : '' ?>
    </p>

    <?php
    $city = City::findOne(['city_id' => $model->company->city]);
    $state = State::findOne(['state_id' => $city->state_id]);
    $country = Country::findOne(['country_id' => $state->country_id]);
    ?>

    <?= DetailView::widget([
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
            [
                'label' => Yii::t('app', 'Position'),
                'value' => $model->getFullRole(),
            ],
            [
                'label' => Yii::t('app', 'Your status'),
                'value' => $model->getFullStatus(),
            ],
            [
                'label' => Yii::t('app', 'Company status'),
                'value' => $model->company->getFullStatus(),
            ],
        ],
    ]) ?>

</div>