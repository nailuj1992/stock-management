<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\entities\City;
use app\models\entities\State;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\entities\User $model */

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update info'), ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Change Password'), ['change'], ['class' => 'btn btn-secondary']) ?>
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
                'label' => Yii::t('app', 'Status'),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

</div>