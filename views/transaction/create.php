<?php

use app\models\entities\Document;
use app\models\entities\Transaction;
use app\models\TextConstants;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var string $company_id */
/** @var app\models\TransactionDto $model */
/** @var app\models\entities\Document[] $documents */
/** @var app\models\entities\Supplier[] $suppliers */
/** @var app\models\entities\Product[] $products */
/** @var app\models\entities\Warehouse[] $warehouses */

$this->title = Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_BUTTON_CREATE);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_TRANSACTIONS_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="transaction-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'document_id')->dropDownList($documents, [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/transaction/get-next-num-transaction']) . '/?document_id=" + $(this).val(), function(data) {
                    $("#transactiondto-num_transaction").val(data);
                    if (Number(data) !== 1) {
                        $("#transactiondto-num_transaction").prop("readonly", true);
                    } else {
                        $("#transactiondto-num_transaction").prop("readonly", false);
                    }
                });
                $.get("' . yii\helpers\Url::to(['/transaction/is-document-for-suppliers']) . '/?document_id=" + $(this).val(), function(data) {
                    $("#transactiondto-supplier_id").prop("disabled", !data);
                    $("#transactiondto-supplier_id").val("");
                });
                $.get("' . yii\helpers\Url::to(['/transaction/is-document-linked-with-other-transaction']) . '/?document_id=" + $(this).val(), function(data) {
                    $("#transactiondto-linked_transaction_id").prop("disabled", !data);
                    $("#transactiondto-linked_transaction_id").val("");
                });
                $.get("' . yii\helpers\Url::to(['/transaction/document-has-expiration']) . '/?document_id=" + $(this).val(), function(data) {
                    $("#transactiondto-expiration_date").prop("disabled", !data);
                    $("#transactiondto-expiration_date").val("");
                });
                $.get("' . yii\helpers\Url::to(['/transaction/get-linked-transactions']) . '/?document_id=" + $(this).val(), function(data) {
                    $("#transactiondto-linked_transaction_id").html(data);
                });
                ',
        ]) ?>

        <?= $form->field($model, 'num_transaction')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number', 'min' => 0]) ?>

        <?php
        $other_transactions = [];
        if (isset($model->document_id) && Document::isDocumentLinkedWithOtherTransaction($model->document_id)) {
            $other_transactions = Transaction::getLinkedTransactions($model->document_id, $company_id);
        }
        ?>
        <?= $form->field($model, 'linked_transaction_id')->dropDownList($other_transactions, [
            'prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT),
            'disabled' => isset($model->document_id) ? !Document::isDocumentLinkedWithOtherTransaction($model->document_id) : true,
            'onchange' => '
                $.get("' . yii\helpers\Url::to(['/transaction/get-supplier-on-transaction']) . '/?transaction_id=" + $(this).val(), function(data) {
                    if (data) {
                        $("#transactiondto-supplier_id").prop("disabled", false);
                        $("#transactiondto-supplier_id").val(data);
                    }
                });
                ',
        ]) ?>

        <?= $form->field($model, 'supplier_id')->dropDownList($suppliers, ['prompt' => Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT), 'disabled' => isset($model->document_id) ? !Document::isDocumentForSuppliers($model->document_id) : true]) ?>

        <?= $form->field($model, 'creation_date')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'date']) ?>

        <?= $form->field($model, 'expiration_date')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'date', 'disabled' => isset($model->document_id) ? !Document::documentHasExpiration($model->document_id) : true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t(TextConstants::APP, TextConstants::BUTTON_SAVE), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>