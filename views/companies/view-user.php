<?php

use app\models\Constants;
use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\entities\City;
use app\models\entities\State;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\entities\UserCompany $model */

$this->title = $model->user->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $company->name, 'url' => ['view', 'company_id' => $company->company_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Current Users'), 'url' => ['list-users', 'company_id' => $model->company_id]];
$this->params['breadcrumbs'][] = $model->user->name;
\yii\web\YiiAsset::register($this);
?>
<div class="company-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if ($model->isMember()) {
            $label = Yii::t('app', "Promote to Supervisor");
            $class = "btn btn-outline-success btn-xs";
            $question = Yii::t('app', "Are you sure you want to promote the user {name} to {role}?", ['name' => $model->user->name, 'role' => Yii::t('app', Constants::ROLE_SUPERVISOR)]);
        } elseif ($model->isSupervisor()) {
            $label = Yii::t('app', "Demote to Member");
            $class = "btn btn-outline-warning btn-xs";
            $question = Yii::t('app', "Are you sure you want to demote the user {name} to {role}?", ['name' => $model->user->name, 'role' => Yii::t('app', Constants::ROLE_MEMBER)]);
        }
        if (Utils::isOwnerOfCompany($model->company_id) && !$model->isOwner() && $model->user->isActive()) {
            echo Html::a($label, ['change-role', 'user_id' => $model->user_id, 'company_id' => $model->company_id], [
                'class' => $class,
                'data' => [
                    'confirm' => $question,
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?php
    $city = City::findOne(['city_id' => $model->company->city]);
    $state = State::findOne(['state_id' => $city->state_id]);
    $country = Country::findOne(['country_id' => $state->country_id]);
    ?>

    <?= DetailView::widget([
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
                'label' => Yii::t('app', 'Position'),
                'value' => $model->getFullRole(),
            ],
            [
                'label' => Yii::t('app', 'User Status'),
                'value' => $model->user->getFullStatus(),
            ],
            [
                'label' => Yii::t('app', 'Status in Company'),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

</div>