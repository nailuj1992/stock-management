<?php

use app\models\TextConstants;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\entities\Country $model */

$this->title = Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_VIEW_TITLE, ['name' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_COUNTRIES_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="country-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE), ['update', 'country_id' => $model->country_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_DELETE), ['delete', 'country_id' => $model->country_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t(TextConstants::APP, TextConstants::MESSAGE_CONFIRMATION_DELETE),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'country_id',
            'code',
            'name',
        ],
    ]) ?>

</div>