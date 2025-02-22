<?php

namespace app\models;

use app\models\entities\Document;
use app\models\entities\Supplier;
use app\models\entities\Transaction;
use app\models\entities\TransactionItem;
use app\models\Utils;
use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $transaction_id
 * @property string $num_transaction
 * @property int $document_id
 * @property string $document
 * @property string $creation_date
 * @property string|null $expiration_date
 * @property int|null $linked_transaction_id
 * @property string|null $linked_transaction
 * @property int|null $supplier_id
 * @property string|null $supplier
 * @property string $status
 * @property int $total_before_taxes
 * @property int $total_taxes
 * @property int $total_value
 * @property TransactionItemDto[] $transaction_items
 */
class TransactionDto extends \yii\db\ActiveRecord
{
    public $transaction_id;
    public $num_transaction;
    public $document_id;
    public $document;
    public $creation_date;
    public $expiration_date;
    public $linked_transaction_id;
    public $linked_transaction;
    public $supplier_id;
    public $supplier;
    public $status;
    public $total_before_taxes;
    public $total_taxes;
    public $total_value;
    public $transaction_items;

    public static function tableName()
    {
        return 'transaction';
    }

    public function rules()
    {
        return [
            [['num_transaction', 'document_id', 'creation_date'], 'required'],
            [['document_id', 'linked_transaction_id', 'supplier_id', 'created_by', 'updated_by'], 'integer'],
            [['creation_date', 'expiration_date'], 'safe'],
            [['num_transaction'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 1],
            [['num_transaction', 'document_id'], 'unique', 'targetAttribute' => ['num_transaction', 'document_id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::class, 'targetAttribute' => ['document_id' => 'document_id']],
            [['linked_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => TransactionDto::class, 'targetAttribute' => ['linked_transaction_id' => 'transaction_id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplier_id' => 'supplier_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'transaction_id' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_ID),
            'num_transaction' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_NUM_ID),
            'document_id' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_ID),
            'creation_date' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_CREATION_DATE),
            'expiration_date' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_EXPIRATION_DATE),
            'linked_transaction_id' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_LINKED),
            'supplier_id' => Yii::t(TextConstants::SUPPLIER, TextConstants::SUPPLIER_MODEL_ID),
            'status' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
            'total_before_taxes' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_SUBTOTAL),
            'total_taxes' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_TAXES),
            'total_value' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_TOTAL),
        ];
    }

    public function isActive()
    {
        return Utils::isActive($this->status);
    }

    public function isInactive()
    {
        return Utils::isInactive($this->status);
    }

    public function getFullStatus()
    {
        return Utils::getFullStatus($this->status);
    }

    /**
     * Method to create a new instance of TransactionDto from a Transaction model.
     * @param \app\models\entities\Transaction $model
     * @return TransactionDto
     */
    public static function newTransactionDto(Transaction $model): TransactionDto
    {
        $transactionDto = new TransactionDto();
        $transactionDto->transaction_id = $model->transaction_id;
        $transactionDto->num_transaction = $model->num_transaction;
        $transactionDto->document_id = $model->document_id;
        $transactionDto->document = $model->document->code . ' - ' . $model->document->name;
        $transactionDto->creation_date = Utils::formatDate($model->creation_date);
        $transactionDto->expiration_date = isset($model->expiration_date) ? Utils::formatDate($model->expiration_date) : '';
        $transactionDto->linked_transaction_id = $model->linked_transaction_id;
        $transactionDto->linked_transaction = isset($model->linkedTransaction) ? $model->linkedTransaction->document->code . ' - ' . $model->linkedTransaction->num_transaction : '';
        $transactionDto->supplier_id = $model->supplier_id;
        $transactionDto->supplier = isset($model->supplier) ? $model->supplier->code . ' - ' . $model->supplier->name : '';
        $transactionDto->status = $model->status;
        return $transactionDto;
    }

    /**
     * To get all the transaction items belonging to a transaction.
     * @param mixed $transaction_id
     * @param mixed $company_id
     * @param \app\models\entities\Document $document
     * @param \app\models\TransactionDto $transactionDto
     * @return void
     */
    public static function getTransactionItems($transaction_id, $company_id, Document $document, TransactionDto $transactionDto, $status = null): void
    {
        $transactionItems = TransactionItem::find()
            ->where(['=', 'company_id', $company_id])
            ->andWhere(['transaction_id' => $transaction_id])
            ->andWhere(['=', 'status', isset($status) ? $status : $transactionDto->status])
            ->all();
        if (isset($transactionItems) && !empty($transactionItems)) {
            $subtotal = 0;
            $taxes = 0;
            foreach ($transactionItems as $item) {
                $transactionItemDto = new TransactionItemDto();

                $transactionItemDto->product_id = $item['product_id'];
                $product = $item['product'];
                $transactionItemDto->product = $product->code . ' - ' . $product->name;

                $transactionItemDto->warehouse_id = $item['warehouse_id'];
                $warehouse = $item['warehouse'];
                $transactionItemDto->warehouse = isset($warehouse) ? $warehouse->code . ' - ' . $warehouse->name : '';

                $transactionItemDto->amount = $item['amount'];
                $transactionItemDto->unit_value = $item['unit_value'];
                $transactionItemDto->discount_rate = $item['discount_rate'];
                $transactionItemDto->tax_rate = $item['tax_rate'];
                $transactionItemDto->total_value = self::calculateTotalValueProduct($transactionItemDto->amount, $transactionItemDto->unit_value, $transactionItemDto->discount_rate);
                $transactionDto->transaction_items[] = $transactionItemDto;

                $subtotal += $transactionItemDto->total_value;
                if ($document->hasTaxes() && isset($transactionItemDto->tax_rate)) {
                    $valueTaxes = $subtotal * ($transactionItemDto->tax_rate / 100);
                    $taxes += $valueTaxes;
                }
                $total = $subtotal + $taxes;
            }

            $transactionDto->total_before_taxes = $subtotal;
            $transactionDto->total_taxes = $taxes;
            $transactionDto->total_value = $total;
        }
    }

    /**
     * Calculates the total value for a transaction item, given it's amount, unit value, and discount rate.
     * @param mixed $amountString
     * @param mixed $unitValueString
     * @param mixed $discountRateString
     * @return int|string
     */
    public static function calculateTotalValueProduct($amountString, $unitValueString, $discountRateString)
    {
        if ($amountString && $amountString !== "" && $unitValueString && $unitValueString !== "") {
            $unitValue = (int) $unitValueString;
            $amount = (int) $amountString;
            $discountRate = 0;
            if ($discountRateString && $discountRateString !== "") {
                $discountRate = (int) $discountRateString;
            }
            $totalValue = $amount * $unitValue;
            if ($discountRate > 0) {
                $totalValue = $totalValue - ($totalValue * $discountRate / 100);
            }
            return $totalValue;
        } else {
            return "";
        }
    }
}