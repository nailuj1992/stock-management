<?php

use app\models\Constants;
use app\models\Utils;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\TransactionDto $transactionDto */
/** @var app\models\entities\Transaction $model */
/** @var app\models\entities\Document $document */

$name = $document->code . ' - ' . $model->num_transaction;
$this->title = Yii::t('app', 'Transaction: {name}', ['name' => $name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transactions'), 'url' => ['index']];
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
                'label' => Yii::t('app', 'Supplier'),
                'value' => isset($transactionDto->supplier) && $transactionDto->supplier !== '' ? $transactionDto->supplier : Yii::t('app', 'N/A'),
            ],
            [
                'label' => Yii::t('app', 'Linked Transaction'),
                'value' => isset($transactionDto->linked_transaction) && $transactionDto->linked_transaction !== '' ? $transactionDto->linked_transaction : Yii::t('app', 'N/A'),
            ],
            'creation_date',
            [
                'label' => Yii::t('app', 'Expiration Date'),
                'value' => isset($transactionDto->expiration_date) && $transactionDto->expiration_date !== '' ? $transactionDto->expiration_date : Yii::t('app', 'N/A'),
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'value' => $model->getFullStatus(),
            ],
        ],
    ]) ?>

    <table id="transaction-items-table">
        <tr>
            <th><?= Yii::t('app', 'Product') ?></th>
            <th><?= Yii::t('app', 'Warehouse') ?></th>
            <th><?= Yii::t('app', 'Amount') ?></th>
            <th><?= Yii::t('app', 'Unit Value ($)') ?></th>
            <th><?= Yii::t('app', 'Discount Rate (%)') ?></th>
            <th><?= Yii::t('app', 'Total Value ($)') ?></th>
            <?= $document->has_taxes === Constants::OPTION_YES_DB ? '<th>' . Yii::t('app', 'Tax Rate (%)') . '</th>' : '' ?>
        </tr>
        <?php
        if (!empty($transactionDto->transaction_items)) {
            for ($i = 0; $i < count($transactionDto->transaction_items); $i++) {
                $transaction_item = $transactionDto->transaction_items[$i];
                ?>
                <tr class="form-group">
                    <td class="w20">
                        <?= $transaction_item->product ?>
                    </td>
                    <td class="w20">
                        <?= $transaction_item->warehouse ?>
                    </td>
                    <td class="w10">
                        <?= $transaction_item->amount ?>
                    </td>
                    <td class="w15">
                        <?= $transaction_item->unit_value ?>
                    </td>
                    <td class="w15">
                        <?= $transaction_item->discount_rate ?>
                    </td>
                    <td class="w15">
                        <?= $transaction_item->total_value ?>
                    </td>
                    <?php if ($document->has_taxes === Constants::OPTION_YES_DB) { ?>
                        <td class="w15">
                            <?= $transaction_item->tax_rate ?>
                        </td>
                    <?php } ?>
                </tr>
                <?php
            }
        }
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