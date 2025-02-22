<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use app\models\TextConstants;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t(TextConstants::APP, TextConstants::BUTTON_LOGIN);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t(TextConstants::APP, TextConstants::MESSAGE_REQUIRED_FIELDS_LOGIN) ?></p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_LOGIN), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <?= Html::a(Yii::t(TextConstants::APP, TextConstants::BUTTON_SIGNUP), ['user/create'], ['class' => 'btn btn-light']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>