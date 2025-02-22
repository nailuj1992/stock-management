<?php

use app\models\entities\City;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\TextConstants;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SupplierEdit $model */
/** @var app\models\entities\Supplier $supplier */

$this->title = Yii::t(TextConstants::SUPPLIER, TextConstants::SUPPLIER_TITLE_UPDATE, [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_SUPPLIERS_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'supplier_id' => $model->supplier_id]];
$this->params['breadcrumbs'][] = Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE);
?>
<div class="supplier-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="supplier-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'type' => 'number']) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <?php $countries = Country::getCountries(); ?>
        <?php $states = State::getStates($model->country); ?>
        <?php $cities = City::getCities($model->state); ?>
        <?= $form->field($model, 'country')->dropDownList($countries, [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/state/dynamic-states']) . '/?country_id=" + $(this).val(), function(data) {
                    $("#supplieredit-state").html(data);
                    $("#supplieredit-city").html("<option value=\"\">' . Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT) . '</option>");
                });',
        ]) ?>

        <?= $form->field($model, 'state')->dropDownList($states, [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/city/dynamic-cities']) . '/?state_id=" + $(this).val(), function(data) {
                    $("#supplieredit-city").html(data);
                });',
        ]) ?>

        <?= $form->field($model, 'city')->dropDownList($cities, ['prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_SAVE), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>