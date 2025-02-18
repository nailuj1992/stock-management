<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\KardexSearchDto $model */
/** @var app\models\entities\Product[] $products */
/** @var app\models\entities\Warehouse[] $warehouses */
/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = Yii::t('app', 'Kardex');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kardex-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="kardex-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'product_id')->dropDownList($products, ['prompt' => Yii::t('app', 'Select...')]) ?>

        <?= $form->field($model, 'warehouse_id')->dropDownList($warehouses, ['prompt' => Yii::t('app', 'Empty')]) ?>

        <?= $form->field($model, 'cutoff_date')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'date']) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <?php if (isset($dataProvider)) { ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => Yii::t('app', 'Date'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->creation_date;
                            },
                ],
                [
                    'attribute' => Yii::t('app', 'Transaction'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->transaction;
                            },
                ],
                [
                    'attribute' => Yii::t('app', 'Unit Value'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return '$ ' . $model->unit_value;
                            },
                ],
                [
                    'attribute' => Yii::t('app', 'Amount Input'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                if (!isset($model->amount_input)) {
                                    return '-';
                                }
                                return $model->hasOtherTransaction() ? '( ' . $model->amount_input . ' )' : $model->amount_input;
                            },
                ],
                [
                    'attribute' => Yii::t('app', 'Value Input'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                if (!isset($model->total_value_input)) {
                                    return '-';
                                }
                                return $model->hasOtherTransaction() ? '$ ( ' . $model->total_value_input . ' )' : '$ ' . $model->total_value_input;
                            },
                ],
                [
                    'attribute' => Yii::t('app', 'Amount Output'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                if (!isset($model->amount_output)) {
                                    return '-';
                                }
                                return $model->hasOtherTransaction() ? '( ' . $model->amount_output . ' )' : $model->amount_output;
                            },
                ],
                [
                    'attribute' => Yii::t('app', 'Value Output'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                if (!isset($model->total_value_output)) {
                                    return '-';
                                }
                                return $model->hasOtherTransaction() ? '$ ( ' . $model->total_value_output . ' )' : '$ ' . $model->total_value_output;
                            },
                ],
                [
                    'attribute' => Yii::t('app', 'Amount Balance'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->amount_balance;
                            },
                ],
                [
                    'attribute' => Yii::t('app', 'Value Balance'),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return '$ ' . $model->total_value_balance;
                            },
                ],
            ],
            'headerRowOptions' => ['class' => 'text-align-center'],
            'rowOptions' => ['class' => 'text-align-center'],
        ]); ?>
    <?php } ?>

</div>