<?php

use app\models\TextConstants;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\entities\City;

/** @var yii\web\View $this */
/** @var app\models\entities\UserCompany $userCompany */
/** @var app\models\CompanyForm $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->title = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_UPDATE, ['name' => $userCompany->company->name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $userCompany->company->name, 'url' => ['view', 'company_id' => $userCompany->company->company_id]];
$this->params['breadcrumbs'][] = Yii::t(TextConstants::APP, TextConstants::BUTTON_UPDATE);
?>
<div class="company-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="company-form">

        <?php $form = ActiveForm::begin([
            'id' => 'update-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'autofocus' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number']) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?php $countries = Country::getCountries(); ?>
        <?php $states = State::getStates($model->country); ?>
        <?php $cities = City::getCities($model->state); ?>
        <?= $form->field($model, 'country')->dropDownList($countries, [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/state/dynamic-states']) . '/?country_id=" + $(this).val(), function(data) {
                    $("#companyform-state").html(data);
                    $("#companyform-city").html("<option value=\"\">' . Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT) . '</option>");
                });',
        ]) ?>

        <?= $form->field($model, 'state')->dropDownList($states, [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/city/dynamic-cities']) . '/?state_id=" + $(this).val(), function(data) {
                    $("#companyform-city").html(data);
                });',
        ]) ?>

        <?= $form->field($model, 'city')->dropDownList($cities, ['prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_SAVE), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>