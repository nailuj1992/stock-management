<?php

use app\models\entities\Document;
use app\models\entities\Supplier;
use app\models\entities\Transaction;
use app\models\entities\TransactionItem;
use app\models\Utils;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\entities\Transaction $model */
/** @var app\models\entities\Document[] $documents */
/** @var app\models\entities\Supplier[] $suppliers */
/** @var app\models\entities\Transaction[] $other_transactions */
/** @var app\models\entities\TransactionItem[] $transaction_items */

$this->title = Yii::t('app', 'Create Transaction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="transaction-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'document_id')->dropDownList($documents, [
            'prompt' => 'Select...',
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/transaction/get-next-num-transaction']) . '/?document_id=" + $(this).val(), function(data) {
                    $("#transaction-num_transaction").val(data);
                });
                $.get("' . yii\helpers\Url::to(['/transaction/is-document-for-suppliers']) . '/?document_id=" + $(this).val(), function(data) {
                    $("#transaction-supplier_id").prop("disabled", !data);
                    $("#transaction-supplier_id").val("");
                });
                $.get("' . yii\helpers\Url::to(['/transaction/is-document-linked-with-other-transaction']) . '/?document_id=" + $(this).val(), function(data) {
                    $("#transaction-linked_transaction_id").prop("disabled", !data);
                    $("#transaction-linked_transaction_id").val("");
                });
                $.get("' . yii\helpers\Url::to(['/transaction/document-has-expiration']) . '/?document_id=" + $(this).val(), function(data) {
                    $("#transaction-expiration_date").prop("disabled", !data);
                    $("#transaction-expiration_date").val("");
                });
                ',
        ]) ?>

        <?= $form->field($model, 'num_transaction')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number', 'min' => 0]) ?>

        <?= $form->field($model, 'supplier_id')->dropDownList($suppliers, ['prompt' => 'Select...', 'disabled' => true]) ?>

        <?= $form->field($model, 'linked_transaction_id')->dropDownList($other_transactions, ['prompt' => 'Select...', 'disabled' => true]) ?>

        <?= $form->field($model, 'creation_date')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'date']) ?>

        <?= $form->field($model, 'expiration_date')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'date', 'disabled' => true]) ?>

        <?php if (!empty($transaction_items)) { ?>
            <div class="form-group">
            </div>
        <?php } ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>