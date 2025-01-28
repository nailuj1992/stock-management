<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\entities\City $city */
/** @var app\models\CityForm $model */

$this->title = Yii::t('app', 'Update City: {name}', ['name' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'city_id' => $model->city_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="city-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
