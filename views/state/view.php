<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\entities\State $model */

$this->title = Yii::t('app', 'State info: {name}', ['name' => $model->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'States'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->name;
\yii\web\YiiAsset::register($this);
?>
<div class="state-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'state_id' => $model->state_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'state_id' => $model->state_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
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
                'label' => Yii::t('app', 'Country'),
                'value' => $country->code . " - " . $country->name,
            ],
        ],
    ]) ?>

</div>
