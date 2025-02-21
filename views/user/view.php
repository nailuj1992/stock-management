<?php

use app\models\TextConstants;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\entities\City;
use app\models\entities\State;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\entities\User $model */

$this->title = Yii::t(TextConstants::APP, TextConstants::TITLE_SETTINGS);
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t(TextConstants::APP, TextConstants::TITLE_UPDATE_INFO), ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t(TextConstants::APP, TextConstants::TITLE_CHANGE_PASSWORD), ['change'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?php
    $city = City::findOne(['city_id' => $model->city]);
    $state = State::findOne(['state_id' => $city->state_id]);
    $country = Country::findOne(['country_id' => $state->country_id]);
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'email:email',
            'name',
            'phone',
            'address',
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
                'label' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_ADDRESS),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

</div>