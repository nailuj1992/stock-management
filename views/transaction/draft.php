<?php

use app\models\Utils;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TransactionDto $transactionDto */
/** @var app\models\entities\Transaction $model */
/** @var app\models\entities\Document $document */
/** @var app\models\entities\Product[] $products */
/** @var app\models\entities\Warehouse[] $warehouses */

$name = $document->code . ' - ' . $model->num_transaction;
$this->title = Yii::t('app', 'Draft Transaction: {name}', ['name' => $name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Draft: {name}', ['name' => $name]);
?>
<div class="transaction-draft">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Utils::belongsToCompany($model->company_id)) {
            $question = Yii::t('app', "Are you sure you want to delete the transaction {code}-{name}?", ['code' => $model->document->code, 'name' => $model->num_transaction]);
            echo Html::a(Yii::t('app', 'Delete'), ['delete-draft', 'transaction_id' => $model->transaction_id], [
                'class' => "btn btn-danger",
                'data' => [
                    'confirm' => $question,
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <div class="transaction-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($transactionDto, 'document')->textInput(['disabled' => true]) ?>

        <?= $form->field($transactionDto, 'num_transaction')->textInput(['disabled' => true, 'type' => 'number']) ?>

        <?= $form->field($transactionDto, 'supplier')->textInput(['disabled' => true]) ?>

        <?= $form->field($transactionDto, 'linked_transaction')->textInput(['disabled' => true]) ?>

        <?= $form->field($transactionDto, 'creation_date')->textInput(['disabled' => true]) ?>

        <?= $form->field($transactionDto, 'expiration_date')->textInput(['disabled' => true]) ?>

        <table id="transaction-items-table">
            <tr>
                <th><?= Yii::t('app', 'Product') ?></th>
                <th><?= Yii::t('app', 'Warehouse') ?></th>
                <th><?= Yii::t('app', 'Amount') ?></th>
                <th><?= Yii::t('app', 'Unit Value ($)') ?></th>
                <th><?= Yii::t('app', 'Discount Rate (%)') ?></th>
                <th><?= Yii::t('app', 'Total Value ($)') ?></th>
            </tr>
            <?php
            if (!empty($transactionDto->transaction_items)) {
                for ($i = 0; $i < count($transactionDto->transaction_items); $i++) {
                    $transaction_item = $transactionDto->transaction_items[$i];
                    ?>
                    <tr class="form-group">
                        <td class="col-2">
                            <?= $form->field($transaction_item, 'product_id')->dropDownList($products, [
                                'prompt' => Yii::t('app', 'Select...'),
                                'id' => 'transaction-item-' . $i . '-product_id',
                                'name' => 'TransactionItemDto[' . $i . '][product_id]',
                                'onchange' => '
                                const warehouseId = $("#transaction-item-' . $i . '-warehouse_id").val()
                                const productId = $(this).val()
                                fillValuesProduct("' . yii\helpers\Url::to(['/transaction/get-product-info']) . '", ' . $transactionDto->transaction_id . ', productId, warehouseId, ' . $i . ')
                                $("#transaction-item-' . $i . '-unit_value").change()
                                ',
                            ])->label(false)->error(false) ?>
                        </td>
                        <td class="col-2">
                            <?= $form->field($transaction_item, 'warehouse_id')->dropDownList($warehouses, [
                                'prompt' => Yii::t('app', 'Empty'),
                                'id' => 'transaction-item-' . $i . '-warehouse_id',
                                'name' => 'TransactionItemDto[' . $i . '][warehouse_id]',
                                'onchange' => '
                                const productId = $("#transaction-item-' . $i . '-product_id").val()
                                const warehouseId = $(this).val()
                                fillValuesProduct("' . yii\helpers\Url::to(['/transaction/get-product-info']) . '", ' . $transactionDto->transaction_id . ', productId, warehouseId, ' . $i . ')
                                $("#transaction-item-' . $i . '-unit_value").change()
                                ',
                            ])->label(false)->error(false) ?>
                        </td>
                        <td class="col-1">
                            <?= $form->field($transaction_item, 'amount')
                                ->textInput([
                                    'maxlength' => true,
                                    'autocomplete' => false,
                                    'type' => 'number',
                                    'min' => 1,
                                    'id' => 'transaction-item-' . $i . '-amount',
                                    'name' => 'TransactionItemDto[' . $i . '][amount]',
                                    'onchange' => '
                                    const unitValueString = $("#transaction-item-' . $i . '-unit_value").val()
                                    const amountString = $(this).val()
                                    const discountRateString = $("#transaction-item-' . $i . '-discount_rate").val()
                                    $("#transaction-item-' . $i . '-total_value").val(calculateTotalValueProduct(amountString, unitValueString, discountRateString))
                                    $("#transaction-item-' . $i . '-total_value").change()
                                    ',
                                ])
                                ->label(false)->error(false) ?>
                        </td>
                        <td class="col-1">
                            <?= $form->field($transaction_item, 'unit_value')
                                ->textInput([
                                    'maxlength' => true,
                                    'autocomplete' => false,
                                    'type' => 'number',
                                    'min' => 0,
                                    'id' => 'transaction-item-' . $i . '-unit_value',
                                    'name' => 'TransactionItemDto[' . $i . '][unit_value]',
                                    'onchange' => '
                                    const amountString = $("#transaction-item-' . $i . '-amount").val()
                                    const unitValueString = $(this).val()
                                    const discountRateString = $("#transaction-item-' . $i . '-discount_rate").val()
                                    $("#transaction-item-' . $i . '-total_value").val(calculateTotalValueProduct(amountString, unitValueString, discountRateString))
                                    $("#transaction-item-' . $i . '-total_value").change()
                                    ',
                                ])
                                ->label(false)->error(false) ?>
                        </td>
                        <td class="col-1">
                            <?= $form->field($transaction_item, 'discount_rate')
                                ->textInput([
                                    'maxlength' => true,
                                    'autocomplete' => false,
                                    'type' => 'number',
                                    'min' => 0,
                                    'max' => 100,
                                    'id' => 'transaction-item-' . $i . '-discount_rate',
                                    'name' => 'TransactionItemDto[' . $i . '][discount_rate]',
                                    'onchange' => '
                                    const unitValueString = $("#transaction-item-' . $i . '-unit_value").val()
                                    const discountRateString = $(this).val()
                                    const amountString = $("#transaction-item-' . $i . '-amount").val()
                                    $("#transaction-item-' . $i . '-total_value").val(calculateTotalValueProduct(amountString, unitValueString, discountRateString))
                                    $("#transaction-item-' . $i . '-total_value").change()
                                    ',
                                ])
                                ->label(false)->error(false) ?>
                        </td>
                        <td class="col-1">
                            <?= $form->field($transaction_item, 'total_value')
                                ->textInput([
                                    'maxlength' => true,
                                    'autocomplete' => false,
                                    'type' => 'number',
                                    'min' => 0,
                                    'disabled' => true,
                                    'id' => 'transaction-item-' . $i . '-total_value',
                                    'name' => 'TransactionItemDto[' . $i . '][total_value]',
                                    'onchange' => '
                                    calculateTotalTransaction("' . yii\helpers\Url::to(['/transaction/document-has-taxes']) . '", ' . $transactionDto->document_id . ', ' . $i . ', $(this).val(), ' . count($transactionDto->transaction_items) . ')
                                    ',
                                ])
                                ->label(false)->error(false) ?>
                            <?= $form->field($transaction_item, 'tax_rate')
                                ->textInput([
                                    'maxlength' => true,
                                    'autocomplete' => false,
                                    'type' => 'hidden',
                                    'min' => 0,
                                    'max' => 100,
                                    'readonly' => true,
                                    'id' => 'transaction-item-' . $i . '-tax_rate',
                                    'name' => 'TransactionItemDto[' . $i . '][tax_rate]',
                                ])
                                ->label(false)->error(false) ?>
                        </td>
                        <?php if (count($transactionDto->transaction_items) > 1) { ?>
                            <td class="align-content-stretch text-align-center">
                                <?= Html::submitButton(Yii::t('app', '-'), ['name' => 'removeRow', 'value' => 'row-' . $i, 'class' => 'btn btn-danger']) ?>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>

        <?= $form->errorSummary($transactionDto) ?>

        <?= $form->field($transactionDto, 'total_before_taxes')
            ->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number', 'disabled' => true]) ?>

        <?= $form->field($transactionDto, 'total_taxes')
            ->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number', 'disabled' => true]) ?>

        <?= $form->field($transactionDto, 'total_value')
            ->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number', 'disabled' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['name' => 'save', 'value' => 'true', 'class' => 'btn btn-success']) ?>
            <?= Html::submitButton(Yii::t('app', 'Add other item'), ['name' => 'addRow', 'value' => 'true', 'class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<script>
    function calculateTotalValueProduct(amountString, unitValueString, discountRateString) {
        if (amountString && amountString !== "" && unitValueString && unitValueString !== "") {
            const unitValue = Number(unitValueString)
            const amount = Number(amountString)
            let discountRate = 0
            if (discountRateString && discountRateString !== "") {
                discountRate = Number(discountRateString)
            }
            let totalValue = amount * unitValue
            if (discountRate > 0) {
                totalValue = totalValue - (totalValue * discountRate / 100)
            }
            return totalValue
        } else {
            return ""
        }
    }

    function fillValuesProduct(url, transactionId, productId, warehouseId, i) {
        $.get(url + "/?transaction_id=" + transactionId + "&product_id=" + productId + "&warehouse_id=" + warehouseId, function (data) {
            if (!data) {
                $("#transaction-item-" + i + "-unit_value").val("")
                $("#transaction-item-" + i + "-tax_rate").val("")
                $("#transaction-item-" + i + "-discount_rate").val("")
            } else {
                const info = JSON.parse(data)
                if (info.value) {
                    $("#transaction-item-" + i + "-unit_value").val(info.value)
                } else {
                    $("#transaction-item-" + i + "-unit_value").val("")
                }
                if (info.discountRate) {
                    $("#transaction-item-" + i + "-discount_rate").val(info.discountRate)
                } else {
                    $("#transaction-item-" + i + "-discount_rate").val("")
                }
                if (info.taxRate) {
                    $("#transaction-item-" + i + "-tax_rate").val(info.taxRate)
                } else {
                    $("#transaction-item-" + i + "-tax_rate").val("")
                }
            }
        });
    }

    function calculateTotalTransaction(url, documentId, row, rowValue, size) {
        $.get(url + "/?document_id=" + documentId, function (hasTaxes) {
            let subtotal = 0
            let taxes = 0
            for (let i = 0; i < size; i++) {
                const taxRate = $("#transaction-item-" + i + "-tax_rate").val()
                let totalValue
                if (i === row) {
                    totalValue = rowValue
                } else {
                    totalValue = $("#transaction-item-" + i + "-total_value").val()
                }

                subtotal += Number(totalValue)
                if (hasTaxes && taxRate && taxRate !== '') {
                    const valueTaxes = subtotal * (Number(taxRate) / 100)
                    taxes += valueTaxes
                }
            }
            const total = subtotal + taxes

            $("#transactiondto-total_before_taxes").val(subtotal)
            $("#transactiondto-total_taxes").val(taxes)
            $("#transactiondto-total_value").val(total)
        });
    }
</script>