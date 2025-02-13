<?php

use app\models\entities\Country;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SupplierEdit $model */

$this->title = Yii::t('app', 'Create Supplier');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Suppliers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="supplier-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'type' => 'number']) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <?php $countries = Country::getCountries(); ?>
        <?= $form->field($model, 'country')->dropDownList($countries, [
            'prompt' => Yii::t('app', 'Select...'),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/state/dynamic-states']) . '/?country_id=" + $(this).val(), function(data) {
                    $("#supplieredit-state").html(data);
                    $("#supplieredit-city").html("<option value=\"\">' . Yii::t('app', 'Select...') . '</option>");
                });',
        ]) ?>

        <?= $form->field($model, 'state')->dropDownList([], [
            'prompt' => Yii::t('app', 'Select...'),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/city/dynamic-cities']) . '/?state_id=" + $(this).val(), function(data) {
                    $("#supplieredit-city").html(data);
                });',
        ]) ?>

        <?= $form->field($model, 'city')->dropDownList([], ['prompt' => Yii::t('app', 'Select...')]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>