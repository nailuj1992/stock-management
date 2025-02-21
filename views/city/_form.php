<?php

use app\models\TextConstants;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\models\entities\Country;
use app\models\entities\State;

/** @var yii\web\View $this */
/** @var app\models\entities\City $city */
/** @var app\models\CityForm $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="city-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'autofocus' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php
    $countries = Country::getCountries();
    if (isset(($model->state))) {
        $state = State::findOne(['state_id' => $model->state]);
        $country = Country::findOne(['country_id' => $state->country_id]);
        $states = State::getStates($country);
    } else {
        $states = [];
    }
    ?>

    <?= $form->field($model, 'country')->dropDownList($countries, [
        'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
        'onchange' => '
                $.get("' . yii\helpers\Url::to(['/state/dynamic-states']) . '/?country_id=" + $(this).val(), function(data) {
                    $("#cityform-state").html(data);
                    $("#cityform-city").html("<option value=\"\">' . Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT) . '</option>");
                });',
    ]) ?>

    <?= $form->field($model, 'state')->dropDownList($states, ['prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_SAVE), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>