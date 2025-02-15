<?php

use app\models\entities\Document;
use app\models\entities\Product;
use app\models\entities\Supplier;
use app\models\entities\Warehouse;
use app\models\entities\Transaction;
use app\models\entities\TransactionItem;
use app\models\TransactionDto;
use app\models\Utils;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TransactionDto $transactionDto */
/** @var app\models\entities\Transaction $model */
/** @var app\models\entities\Document $document */
/** @var app\models\entities\Supplier|null $supplier */
/** @var app\models\entities\Transaction|null $linked_transaction */
/** @var app\models\entities\Product[] $products */
/** @var app\models\entities\Warehouse[] $warehouses */

$name = $document->code . ' - ' . $model->num_transaction;
$this->title = Yii::t('app', 'Draft Transaction: {name}', ['name' => $name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Draft: {name}', ['name' => $name]);
?>
<div class="transaction-draft">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="transaction-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($transactionDto, 'document')->textInput(['disabled' => true]) ?>

        <?= $form->field($transactionDto, 'num_transaction')->textInput(['disabled' => true, 'type' => 'number']) ?>

        <?= $form->field($transactionDto, 'supplier')->textInput(['disabled' => true]) ?>

        <?= $form->field($transactionDto, 'linked_transaction')->textInput(['disabled' => true]) ?>

        <?= $form->field($transactionDto, 'creation_date')->textInput(['disabled' => true]) ?>

        <?= $form->field($transactionDto, 'expiration_date')->textInput(['disabled' => true]) ?>

        <table>
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
                        <td>
                            <?= $form->field($transaction_item, 'product_id')->dropDownList($products, [
                                'prompt' => Yii::t('app', 'Select...'),
                                'id' => 'transaction-item-' . $i . '-product_id',
                                'onchange' => '
                                const warehouseId = $("#transaction-item-' . $i . '-warehouse_id").val()
                                const productId = $(this).val()
                                $.get("' . yii\helpers\Url::to(['/transaction/get-product-info']) . '/?product_id=" + productId + "&warehouse_id=" + warehouseId, function(data) {
                                    if (!data) {
                                        $("#transaction-item-' . $i . '-unit_value").val("")
                                        $("#transaction-item-' . $i . '-tax_rate").val("")
                                        $("#transaction-item-' . $i . '-discount_rate").val("")
                                    } else {
                                        const info = JSON.parse(data)
                                        if (info.value) {
                                            $("#transaction-item-' . $i . '-unit_value").val(info.value)
                                        } else {
                                            $("#transaction-item-' . $i . '-unit_value").val("")
                                        }
                                        if (info.discountRate) {
                                            $("#transaction-item-' . $i . '-discount_rate").val(info.discountRate)
                                        } else {
                                            $("#transaction-item-' . $i . '-discount_rate").val("")
                                        }
                                        if (info.taxRate) {
                                            $("#transaction-item-' . $i . '-tax_rate").val(info.taxRate)
                                        } else {
                                            $("#transaction-item-' . $i . '-tax_rate").val("")
                                        }
                                    }
                                });',
                            ])->label(false)->error(false) ?>
                        </td>
                        <td>
                            <?= $form->field($transaction_item, 'warehouse_id')->dropDownList($warehouses, [
                                'prompt' => Yii::t('app', 'Empty'),
                                'id' => 'transaction-item-' . $i . '-warehouse_id',
                            ])->label(false)->error(false) ?>
                        </td>
                        <td>
                            <?= $form->field($transaction_item, 'amount')
                                ->textInput([
                                    'maxlength' => true,
                                    'autocomplete' => false,
                                    'type' => 'number',
                                    'min' => 0,
                                    'id' => 'transaction-item-' . $i . '-amount',
                                    'onchange' => '
                                    const unitValueString = $("#transaction-item-' . $i . '-unit_value").val()
                                    const amountString = $(this).val()
                                    const discountRateString = $("#transaction-item-' . $i . '-discount_rate").val()
                                    $("#transaction-item-' . $i . '-total_value").val(calculateTotalValueProduct(amountString, unitValueString, discountRateString))
                                    ',
                                ])
                                ->label(false)->error(false) ?>
                        </td>
                        <td>
                            <?= $form->field($transaction_item, 'unit_value')
                                ->textInput([
                                    'maxlength' => true,
                                    'autocomplete' => false,
                                    'type' => 'number',
                                    'min' => 0,
                                    'id' => 'transaction-item-' . $i . '-unit_value',
                                    'onchange' => '
                                    const amountString = $("#transaction-item-' . $i . '-amount").val()
                                    const unitValueString = $(this).val()
                                    const discountRateString = $("#transaction-item-' . $i . '-discount_rate").val()
                                    $("#transaction-item-' . $i . '-total_value").val(calculateTotalValueProduct(amountString, unitValueString, discountRateString))
                                    ',
                                ])
                                ->label(false)->error(false) ?>
                        </td>
                        <td>
                            <?= $form->field($transaction_item, 'discount_rate')
                                ->textInput([
                                    'maxlength' => true,
                                    'autocomplete' => false,
                                    'type' => 'number',
                                    'min' => 0,
                                    'max' => 100,
                                    'id' => 'transaction-item-' . $i . '-discount_rate',
                                    'onchange' => '
                                    const unitValueString = $("#transaction-item-' . $i . '-unit_value").val()
                                    const discountRateString = $(this).val()
                                    const amountString = $("#transaction-item-' . $i . '-amount").val()
                                    $("#transaction-item-' . $i . '-total_value").val(calculateTotalValueProduct(amountString, unitValueString, discountRateString))
                                    ',
                                ])
                                ->label(false)->error(false) ?>
                        </td>
                        <td>
                            <?= $form->field($transaction_item, 'total_value')
                                ->textInput([
                                    'maxlength' => true,
                                    'autocomplete' => false,
                                    'type' => 'number',
                                    'min' => 0,
                                    'disabled' => true,
                                    'id' => 'transaction-item-' . $i . '-total_value',
                                ])
                                ->label(false)->error(false) ?>
                        </td>
                        <td>
                            <?= $form->field($transaction_item, 'tax_rate')
                                ->textInput([
                                    'maxlength' => true,
                                    'autocomplete' => false,
                                    'type' => 'number',
                                    'min' => 0,
                                    'max' => 100,
                                    'disabled' => true,
                                    'id' => 'transaction-item-' . $i . '-tax_rate',
                                ])
                                ->label(false)->error(false) ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
            <?= Html::submitButton(Yii::t('app', 'Add item'), ['name' => 'addRow', 'value' => 'true', 'class' => 'btn btn-primary']) ?>
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
</script>