<?php

use app\models\TextConstants;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\entities\ApplicationCompany $model */

$this->title = Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_DENY);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_INDEX_TITLE), 'url' => ['companies/']];
$this->params['breadcrumbs'][] = ['label' => $model->company->name, 'url' => ['companies/view', 'company_id' => $model->company_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_LIST_USERS_TITLE), 'url' => ['companies/list-users', 'company_id' => $model->company_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_LIST_USERS_PENDING), 'url' => ['list-pending', 'company_id' => $model->company_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-company-deny">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="application-company-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'comment_company')->textarea(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_SAVE), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>