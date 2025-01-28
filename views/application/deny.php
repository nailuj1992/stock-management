<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\entities\ApplicationCompany $model */

$this->title = Yii::t('app', 'Deny Application');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['companies/']];
$this->params['breadcrumbs'][] = ['label' => $model->company->name, 'url' => ['companies/view', 'company_id' => $model->company_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Current Users'), 'url' => ['companies/list-users', 'company_id' => $model->company_id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pending Applications'), 'url' => ['list-pending', 'company_id' => $model->company_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-company-deny">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="application-company-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'comment_company')->textarea(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>