<?php

use app\models\Constants;
use app\models\TextConstants;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TransactionDto $transactionDto */
/** @var app\models\entities\Transaction $model */
/** @var app\models\entities\Document $document */

$name = $document->code . ' - ' . $model->num_transaction;
$this->title = Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_TITLE_VIEW, ['name' => $name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(TextConstants::INDEX, TextConstants::INDEX_TRANSACTIONS_TITLE), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transaction-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $transactionDto,
        'attributes' => [
            'document',
            'num_transaction',
            [
                'label' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_LINKED),
                'value' => isset($transactionDto->linked_transaction) && $transactionDto->linked_transaction !== '' ? $transactionDto->linked_transaction : Constants::NA,
            ],
            [
                'label' => Yii::t(TextConstants::SUPPLIER, TextConstants::SUPPLIER_MODEL_ID),
                'value' => isset($transactionDto->supplier) && $transactionDto->supplier !== '' ? $transactionDto->supplier : Constants::NA,
            ],
            'creation_date',
            [
                'label' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_EXPIRATION_DATE),
                'value' => isset($transactionDto->expiration_date) && $transactionDto->expiration_date !== '' ? $transactionDto->expiration_date : Constants::NA,
            ],
            [
                'label' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

    <table id="transaction-items-table">
        <tr>
            <th><?= Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_ID) ?></th>
            <th><?= Yii::t(TextConstants::WAREHOUSE, TextConstants::WAREHOUSE_MODEL_ID) ?></th>
            <th><?= Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_AMOUNT) ?></th>
            <th><?= Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_UNIT_VALUE) ?></th>
            <th><?= Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_DISCOUNT_RATE) ?></th>
            <th><?= Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_TOTAL_VALUE) ?></th>
            <?= $document->hasTaxes() ? '<th>' . Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_TAX_RATE) . '</th>' : '' ?>
        </tr>
        <?php
        $form = ActiveForm::begin();
        if (!empty($transactionDto->transaction_items)) {
            for ($i = 0; $i < count($transactionDto->transaction_items); $i++) {
                $transaction_item = $transactionDto->transaction_items[$i];
                ?>
                <tr class="form-group">
                    <td class="col-2">
                        <?= $form->field($transaction_item, 'product')->textInput(['disabled' => true])->label(false) ?>
                    </td>
                    <td class="col-2">
                        <?= $form->field($transaction_item, 'warehouse')->textInput(['disabled' => true])->label(false) ?>
                    </td>
                    <td class="col-1">
                        <?= $form->field($transaction_item, 'amount')->textInput(['disabled' => true, 'type' => 'number'])->label(false) ?>
                    </td>
                    <td class="col-1">
                        <?= $form->field($transaction_item, 'unit_value')->textInput(['disabled' => true, 'type' => 'number'])->label(false) ?>
                    </td>
                    <td class="col-1">
                        <?= $form->field($transaction_item, 'discount_rate')->textInput(['disabled' => true, 'type' => 'number'])->label(false) ?>
                    </td>
                    <td class="col-1">
                        <?= $form->field($transaction_item, 'total_value')->textInput(['disabled' => true, 'type' => 'number'])->label(false) ?>
                    </td>
                    <?php if ($document->hasTaxes()) { ?>
                        <td class="col-1">
                            <?= $form->field($transaction_item, 'tax_rate')->textInput(['disabled' => true, 'type' => 'number'])->label(false) ?>
                        </td>
                    <?php } ?>
                </tr>
                <?php
            }
        }
        ActiveForm::end();
        ?>
    </table>

    <?= DetailView::widget([
        'model' => $transactionDto,
        'attributes' => [
            'total_before_taxes',
            'total_taxes',
            'total_value',
        ],
    ]) ?>

</div>