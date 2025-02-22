<?php

use app\models\Constants;
use app\models\TextConstants;
use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\entities\City;
use app\models\entities\State;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\entities\UserCompany $model */

$this->title = $model->user->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $company->name, 'url' => ['view', 'company_id' => $company->company_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_LIST_USERS_TITLE), 'url' => ['list-users', 'company_id' => $model->company_id]];
$this->params['breadcrumbs'][] = $model->user->name;
\yii\web\YiiAsset::register($this);
?>
<div class="company-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if ($model->isMember()) {
            $label = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_PROMOTE_SUPERVISOR);
            $class = "btn btn-outline-success btn-xs";
            $question = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_CONFIRMATION_PROMOTE, ['name' => $model->user->name, 'role' => Yii::t(TextConstants::ROLE, TextConstants::ROLE_SUPERVISOR)]);
        } elseif ($model->isSupervisor()) {
            $label = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_DEMOTE_MEMBER);
            $class = "btn btn-outline-warning btn-xs";
            $question = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_CONFIRMATION_DEMOTE, ['name' => $model->user->name, 'role' => Yii::t(TextConstants::ROLE, TextConstants::ROLE_MEMBER)]);
        }
        if (Utils::isOwnerOfCompany($model->company_id) && !$model->isOwner() && $model->user->isActive() && $model->isActive()) {
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
                'label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_USER_STATUS),
                'value' => $model->user->getFullStatus(),
            ],
            [
                'label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_STATUS_IN_COMPANY),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

</div>