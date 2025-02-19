<?php

use app\models\TextConstants;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\entities\Country;
use app\models\entities\State;

/** @var yii\web\View $this */
/** @var app\models\entities\City $model */

$this->title = Yii::t('app', "City info: {name}", ['name' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="city-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'city_id' => $model->city_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'city_id' => $model->city_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t(TextConstants::APP, TextConstants::MESSAGE_CONFIRMATION_DELETE),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    $state = State::findOne(['state_id' => $model->state_id]);
    $country = Country::findOne(['country_id' => $state->country_id]);
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'city_id',
            'code',
            'name',
            [
                'label' => Yii::t(TextConstants::STATE, TextConstants::STATE_MODEL_ID),
                'value' => $state->code . " - " . $state->name,
            ],
            [
                'label' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
                'value' => $country->code . " - " . $country->name,
            ],
        ],
    ]) ?>

</div>