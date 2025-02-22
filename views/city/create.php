<?php

use app\models\TextConstants;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\entities\City $city */
/** @var app\models\CityForm $model */

$this->title = Yii::t(TextConstants::CITY, TextConstants::CITY_BUTTON_CREATE);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_CITIES_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>