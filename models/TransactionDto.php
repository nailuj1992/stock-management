<?php

namespace app\models;

use app\models\entities\Document;
use app\models\entities\Supplier;
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
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'num_transaction' => Yii::t('app', '# Transaction'),
            'document_id' => Yii::t('app', 'Document'),
            'creation_date' => Yii::t('app', 'Creation Date'),
            'expiration_date' => Yii::t('app', 'Expiration Date'),
            'linked_transaction_id' => Yii::t('app', 'Linked Transaction'),
            'supplier_id' => Yii::t('app', 'Supplier'),
            'status' => Yii::t('app', 'Status'),
            'total_before_taxes' => Yii::t('app', 'Subtotal'),
            'total_taxes' => Yii::t('app', 'Taxes'),
            'total_value' => Yii::t('app', 'Total'),
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
}