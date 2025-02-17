<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\UserSignUp $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->title = Yii::t('app', 'Sign Up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">

        <?php $form = ActiveForm::begin([
            'id' => 'signup-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'autofocus' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'repassword')->passwordInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number']) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?php $countries = Country::getCountries(); ?>
        <?= $form->field($model, 'country')->dropDownList($countries, [
            'prompt' => Yii::t('app', 'Select...'),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/state/dynamic-states']) . '/?country_id=" + $(this).val(), function(data) {
                    $("#usersignup-state").html(data);
                    $("#usersignup-city").html("<option value=\"\">' . Yii::t('app', 'Select...') . '</option>");
                });',
        ]) ?>

        <?= $form->field($model, 'state')->dropDownList([], [
            'prompt' => Yii::t('app', 'Select...'),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/city/dynamic-cities']) . '/?state_id=" + $(this).val(), function(data) {
                    $("#usersignup-city").html(data);
                });',
        ]) ?>

        <?= $form->field($model, 'city')->dropDownList([], ['prompt' => Yii::t('app', 'Select...')]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>