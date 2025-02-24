<?php

use app\models\TextConstants;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\entities\State $model */

$this->title = Yii::t(TextConstants::STATE, TextConstants::STATE_VIEW_TITLE, ['name' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_STATES_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="state-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE), ['update', 'state_id' => $model->state_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_DELETE), ['delete', 'state_id' => $model->state_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t(TextConstants::APP, TextConstants::MESSAGE_CONFIRMATION_DELETE),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    $country = Country::findOne(['country_id' => $model->country_id]);
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'state_id',
            'code',
            'name',
            [
                'label' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
                'value' => $country->code . " - " . $country->name,
            ],
        ],
    ]) ?>

</div>