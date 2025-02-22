<?php

use app\models\TextConstants;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ExistencesDto $model */
/** @var app\models\entities\Product[] $products */
/** @var app\models\entities\Warehouse[] $warehouses */
/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = Yii::t(TextConstants::INDEX, TextConstants::INDEX_EXISTENCES_TITLE);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="existences-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="existences-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'product_id')->dropDownList($products, ['prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)]) ?>

        <?= $form->field($model, 'warehouse_id')->dropDownList($warehouses, ['prompt' => Yii::t(TextConstants::APP, textConstants::OPTION_EMPTY)]) ?>

        <?= $form->field($model, 'cutoff_date')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'date']) ?>

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
                    'attribute' => Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_ID),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->product;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::WAREHOUSE, TextConstants::WAREHOUSE_MODEL_ID),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->warehouse;
                            },
                ],
                [
                    'attribute' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_AMOUNT_DIFFERENCE),
                    'format' => 'raw',
                    'value' => function ($model) {
                                return $model->amountDifference;
                            },
                ],
            ],
            'headerRowOptions' => ['class' => 'text-align-center'],
            'rowOptions' => ['class' => 'text-align-center'],
        ]); ?>
    <?php } ?>

</div>