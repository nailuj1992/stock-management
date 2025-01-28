<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\entities\ApplicationCompany $model */

$this->title = Yii::t('app', 'Create Application');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['companies/']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Applications'), 'url' => ['index', 'company_id' => $company_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-company-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="application-company-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'comment_user')->textarea(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>