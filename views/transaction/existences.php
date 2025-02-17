<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ExistencesDto $model */
/** @var app\models\entities\Product[] $products */
/** @var app\models\entities\Warehouse[] $warehouses */
/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = Yii::t('app', 'Existences');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="existences-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="existences-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'product_id')->dropDownList($products, ['prompt' => Yii::t('app', 'Select...')]) ?>

        <?= $form->field($model, 'warehouse_id')->dropDownList($warehouses, ['prompt' => Yii::t('app', 'Empty')]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <?php if (isset($dataProvider) && $dataProvider->totalCount > 0) { ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => Yii::t('app', 'Product'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->product;
                            },
                ],
                [
                    'attribute' => Yii::t('app', 'Warehouse'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->warehouse;
                            },
                ],
                [
                    'attribute' => Yii::t('app', 'Amount'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->amountDifference;
                            },
                ],
            ],
        ]); ?>
    <?php } ?>

</div>