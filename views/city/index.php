<?php

use app\models\entities\City;
use app\models\entities\State;
use app\models\entities\Country;
use app\models\TextConstants;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t(TextConstants::INDEX, TextConstants::INDEX_CITIES_TITLE);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t(TextConstants::CITY, TextConstants::CITY_BUTTON_CREATE), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'city_id',
            'code',
            'name',
            [
                'attribute' => Yii::t(TextConstants::STATE, TextConstants::STATE_MODEL_ID),
                'format' => 'raw',
                'value' => function ($model) {
                        $state = State::findOne(['state_id' => $model->state_id]);
                        return $state->code . " - " . $state->name;
                    },
            ],
            [
                'attribute' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
                'format' => 'raw',
                'value' => function ($model) {
                        $state = State::findOne(['state_id' => $model->state_id]);
                        $country = Country::findOne(['country_id' => $state->country_id]);
                        return $country->code . " - " . $country->name;
                    },
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, City $model) {
                        return Url::toRoute([$action, 'city_id' => $model->city_id]);
                    }
            ],
        ],
    ]); ?>


</div>