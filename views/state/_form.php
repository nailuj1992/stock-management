<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\entities\State $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="state-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'autofocus' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php $countries = Country::getCountries(); ?>

    <?= $form->field($model, 'country_id')->dropDownList($countries, ['prompt' => Yii::t('app', 'Select...')]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>