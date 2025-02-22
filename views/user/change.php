<?php

use app\models\TextConstants;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\entities\User $user */
/** @var app\models\UserPassword $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->title = Yii::t(TextConstants::APP, TextConstants::TITLE_CHANGE_PASSWORD);
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

        <?= $form->field($model, 'oldpassword')->passwordInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <?= $form->field($model, 'repassword')->passwordInput(['maxlength' => true, 'autocomplete' => false]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_SAVE), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>