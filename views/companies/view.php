<?php

use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\entities\City;
use app\models\entities\State;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\entities\UserCompany $model */

$this->title = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_VIEW_TITLE, ['name' => $model->company->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->company->name;
\yii\web\YiiAsset::register($this);
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Utils::isOwnerOfCompany($model->company->company_id) ? Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE), ['update', 'company_id' => $model->company->company_id], ['class' => 'btn btn-primary']) : '' ?>
        <?= Utils::isOwnerOrSupervisorOfCompany($model->company->company_id) ? Html::a(Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_BUTTON_LIST_USERS), ['list-users', 'company_id' => $model->company->company_id], ['class' => 'btn btn-outline-info']) : '' ?>
        <?= Utils::isOwnerOrSupervisorOfCompany($model->company->company_id) ? Html::a(Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_BUTTON_CREATE_USER), ['create-user', 'company_id' => $model->company->company_id], ['class' => 'btn btn-outline-success']) : '' ?>
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
                'label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_POSITION),
                'value' => $model->getFullRole(),
            ],
            [
                'label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_YOUR_STATUS),
                'value' => $model->getFullStatus(),
            ],
            [
                'label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_STATUS),
                'value' => $model->company->getFullStatus(),
            ],
        ],
    ]) ?>

</div>