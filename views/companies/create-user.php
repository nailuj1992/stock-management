<?php

use app\models\TextConstants;
use app\models\Utils;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use app\models\entities\Country;

/** @var yii\web\View $this */
/** @var app\models\UserCompanyForm $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->title = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_BUTTON_CREATE_USER);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $company->name, 'url' => ['view', 'company_id' => $company->company_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_LIST_USERS_TITLE), 'url' => ['list-users', 'company_id' => $company->company_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-form">

        <?php $form = ActiveForm::begin([
            'id' => 'usercreate-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'autofocus' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number']) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?php $roles = Utils::getRoles(); ?>
        <?= $form->field($model, 'role')->dropDownList($roles, ['prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)]) ?>

        <?php $countries = Country::getCountries(); ?>
        <?= $form->field($model, 'country')->dropDownList($countries, [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/state/dynamic-states']) . '/?country_id=" + $(this).val(), function(data) {
                    $("#usercompanyform-state").html(data);
                    $("#usercompanyform-city").html("<option value=\"\">' . Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT) . '</option>");
                });',
        ]) ?>

        <?= $form->field($model, 'state')->dropDownList([], [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/city/dynamic-cities']) . '/?state_id=" + $(this).val(), function(data) {
                    $("#usercompanyform-city").html(data);
                });',
        ]) ?>

        <?= $form->field($model, 'city')->dropDownList([], ['prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_SAVE), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>