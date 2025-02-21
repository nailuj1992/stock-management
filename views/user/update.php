<?php

use app\models\TextConstants;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\entities\City;

/** @var yii\web\View $this */
/** @var app\models\entities\User $user */
/** @var app\models\UserEdit $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->title = Yii::t(TextConstants::APP, TextConstants::TITLE_UPDATE_INFO);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::APP, TextConstants::TITLE_SETTINGS), 'url' => ['view']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">

        <?php $form = ActiveForm::begin([
            'id' => 'update-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'autofocus' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number']) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?php $countries = Country::getCountries(); ?>
        <?php $states = State::getStates($model->country); ?>
        <?php $cities = City::getCities($model->state); ?>
        <?= $form->field($model, 'country')->dropDownList($countries, [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/state/dynamic-states']) . '/?country_id=" + $(this).val(), function(data) {
                    $("#useredit-state").html(data);
                    $("#useredit-city").html("<option value=\"\">' . Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT) . '</option>");
                });',
        ]) ?>

        <?= $form->field($model, 'state')->dropDownList($states, [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/city/dynamic-cities']) . '/?state_id=" + $(this).val(), function(data) {
                    $("#useredit-city").html(data);
                });',
        ]) ?>

        <?= $form->field($model, 'city')->dropDownList($cities, ['prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_SAVE), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>