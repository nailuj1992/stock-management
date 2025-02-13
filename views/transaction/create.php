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
/** @var app\models\TransactionDto $model */
/** @var app\models\entities\Document[] $documents */
/** @var app\models\entities\Supplier[] $suppliers */
/** @var app\models\entities\Transaction[] $other_transactions */
/** @var app\models\entities\Product[] $products */
/** @var app\models\entities\Warehouse[] $warehouses */

$this->title = Yii::t('app', 'Create Transaction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transactions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="transaction-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'document_id')->dropDownList($documents, [
            'prompt' => Yii::t('app', 'Select...'),
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

        <?= $form->field($model, 'supplier_id')->dropDownList($suppliers, ['prompt' => Yii::t('app', 'Select...'), 'disabled' => true]) ?>

        <?= $form->field($model, 'linked_transaction_id')->dropDownList($other_transactions, ['prompt' => Yii::t('app', 'Select...'), 'disabled' => true]) ?>

        <?= $form->field($model, 'creation_date')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'date']) ?>

        <?= $form->field($model, 'expiration_date')->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'date', 'disabled' => true]) ?>

        <table>
            <tr>
                <th><?= Yii::t('app', 'Product') ?></th>
                <th><?= Yii::t('app', 'Warehouse') ?></th>
                <th><?= Yii::t('app', 'Amount') ?></th>
                <th><?= Yii::t('app', 'Unit Value') ?></th>
                <th><?= Yii::t('app', 'Discount Rate (%)') ?></th>
                <th><?= Yii::t('app', 'Total Value') ?></th>
            </tr>
            <?php
            if (!empty($model->transaction_items)) {
                for ($i = 0; $i < count($model->transaction_items); $i++) {
                    $transaction_item = $model->transaction_items[$i];
                    ?>
                    <tr class="form-group">
                        <td>
                            <?= $form->field($transaction_item, 'product_id')->dropDownList($products, [
                                'prompt' => Yii::t('app', 'Select...'),
                                'id' => 'transaction-item-' . $i . '-product_id',
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
                                ->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number', 'min' => 0, 'id' => 'transaction-item-' . $i . '-amount'])
                                ->label(false)->error(false) ?>
                        </td>
                        <td>
                            <?= $form->field($transaction_item, 'unit_value')
                                ->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number', 'min' => 0, 'id' => 'transaction-item-' . $i . '-unit_value'])
                                ->label(false)->error(false) ?>
                        </td>
                        <td>
                            <?= $form->field($transaction_item, 'discount_rate')
                                ->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number', 'min' => 0, 'id' => 'transaction-item-' . $i . '-discount_rate'])
                                ->label(false)->error(false) ?>
                        </td>
                        <td>
                            <?= $form->field($transaction_item, 'total_value')
                                ->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number', 'min' => 0, 'disabled' => true, 'id' => 'transaction-item-' . $i . '-total_value'])
                                ->label(false)->error(false) ?>
                        </td>
                        <td>
                            <?= $form->field($transaction_item, 'tax_rate')
                                ->textInput(['maxlength' => true, 'autocomplete' => false, 'type' => 'number', 'min' => 0, 'disabled' => true, 'id' => 'transaction-item-' . $i . '-tax_rate'])
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
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>