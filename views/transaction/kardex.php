<?php

use app\models\Constants;
use app\models\TextConstants;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\KardexSearchDto $model */
/** @var app\models\entities\Product[] $products */
/** @var app\models\entities\Warehouse[] $warehouses */
/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = Yii::t(TextConstants::INDEX, TextConstants::INDEX_KARDEX_TITLE);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kardex-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="kardex-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'product_id')->dropDownList($products, ['prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)]) ?>

        <?= $form->field($model, 'warehouse_id')->dropDownList($warehouses, ['prompt' => Yii::t(TextConstants::APP, textConstants::OPTION_EMPTY)]) ?>

        <?= $form->field($model, 'initial_date')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'date']) ?>

        <?= $form->field($model, 'final_date')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'date']) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_SEARCH), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <?php if (isset($dataProvider)) { ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_DATE),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->creation_date;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_ID),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->transaction;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_UNIT_VALUE),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return '$ ' . $model->unit_value;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_AMOUNT_INPUT),
                    'format' => 'raw',
                    'value' => function ($model) {
                                if (!isset($model->amount_input)) {
                                    return Constants::MINUS;
                                }
                                return $model->hasOtherTransaction() ? '( ' . $model->amount_input . ' )' : $model->amount_input;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_VALUE_INPUT),
                    'format' => 'raw',
                    'value' => function ($model) {
                                if (!isset($model->total_value_input)) {
                                    return Constants::MINUS;
                                }
                                return $model->hasOtherTransaction() ? '$ ( ' . $model->total_value_input . ' )' : '$ ' . $model->total_value_input;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_AMOUNT_OUTPUT),
                    'format' => 'raw',
                    'value' => function ($model) {
                                if (!isset($model->amount_output)) {
                                    return Constants::MINUS;
                                }
                                return $model->hasOtherTransaction() ? '( ' . $model->amount_output . ' )' : $model->amount_output;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_VALUE_OUTPUT),
                    'format' => 'raw',
                    'value' => function ($model) {
                                if (!isset($model->total_value_output)) {
                                    return Constants::MINUS;
                                }
                                return $model->hasOtherTransaction() ? '$ ( ' . $model->total_value_output . ' )' : '$ ' . $model->total_value_output;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_AMOUNT_BALANCE),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->amount_balance;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_VALUE_BALANCE),
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