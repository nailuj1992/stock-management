<?php

use app\models\entities\Country;
use app\models\TextConstants;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t(TextConstants::INDEX, TextConstants::INDEX_COUNTRIES_TITLE);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_BUTTON_CREATE), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'country_id',
            'code',
            'name',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Country $model) {
                    return Url::toRoute([$action, 'country_id' => $model->country_id]);
                 }
            ],
        ],
    ]); ?>


</div>
