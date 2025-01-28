<?php

use app\models\entities\Document;
use app\models\Utils;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\entities\Document $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php $actions = Document::getActionsIntendedFor(); ?>
    <?= $form->field($model, 'intended_for')->radioList($actions) ?>

    <?php $apply = Document::getPeopleApplyFor(); ?>
    <?= $form->field($model, 'apply_for')->radioList($apply) ?>

    <?php $taxes = Utils::getYesNoOptions(); ?>
    <?= $form->field($model, 'has_taxes')->radioList($taxes) ?>

    <?php $expiration = Utils::getYesNoOptions(); ?>
    <?= $form->field($model, 'has_expiration')->radioList($expiration) ?>

    <?php $transaction = Utils::getYesNoOptions(); ?>
    <?= $form->field($model, 'has_other_transaction')->radioList($transaction) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>