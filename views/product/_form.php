<?php

use app\models\entities\Product;
use app\models\Utils;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\entities\Product $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

    <?php $existences = Utils::getYesNoOptions(); ?>
    <?= $form->field($model, 'has_existences')->radioList($existences) ?>

    <?= $form->field($model, 'tax_rate')->textInput(['type' => 'number', 'min' => 0, 'max' => 100]) ?>

    <?= $form->field($model, 'discount_rate')->textInput(['type' => 'number', 'min' => 0, 'max' => 100]) ?>

    <?= $form->field($model, 'minimum_stock')->textInput(['type' => 'number', 'min' => 0]) ?>

    <?= $form->field($model, 'sugested_value')->textInput(['type' => 'number', 'min' => 0]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>