<?php

use app\models\entities\Country;
use app\models\TextConstants;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\WarehouseEdit $model */

$this->title = Yii::t(TextConstants::WAREHOUSE, TextConstants::WAREHOUSE_BUTTON_CREATE);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_WAREHOUSES_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="warehouse-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="warehouse-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <?php $countries = Country::getCountries(); ?>
        <?= $form->field($model, 'country')->dropDownList($countries, [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/state/dynamic-states']) . '/?country_id=" + $(this).val(), function(data) {
                    $("#warehouseedit-state").html(data);
                    $("#warehouseedit-city").html("<option value=\"\">' . Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT) . '</option>");
                });',
        ]) ?>

        <?= $form->field($model, 'state')->dropDownList([], [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/city/dynamic-cities']) . '/?state_id=" + $(this).val(), function(data) {
                    $("#warehouseedit-city").html(data);
                });',
        ]) ?>

        <?= $form->field($model, 'city')->dropDownList([], ['prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_SAVE), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>