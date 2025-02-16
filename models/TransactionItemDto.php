<?php

namespace app\models;

use app\models\entities\Product;
use app\models\entities\Warehouse;
use app\models\Utils;
use Yii;

/**
 * This is the model class for table "transaction_item".
 *
 * @property int $transaction_item_id
 * @property int $amount
 * @property float $unit_value
 * @property float|null $tax_rate
 * @property float|null $discount_rate
 * @property int $product_id
 * @property string $product
 * @property int|null $warehouse_id
 * @property string|null $warehouse
 * @property string $status
 * @property float $total_value
 */
class TransactionItemDto extends \yii\db\ActiveRecord
{
    public $transaction_item_id;
    public $amount;
    public $unit_value;
    public $total_value;
    public $tax_rate;
    public $discount_rate;
    public $product_id;
    public $product;
    public $warehouse_id;
    public $warehouse;
    public $status;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'unit_value', 'product_id'], 'required'],
            [['amount', 'product_id', 'warehouse_id'], 'integer'],
            [['unit_value', 'tax_rate', 'discount_rate', 'total_value'], 'number'],
            [['status'], 'string', 'max' => 1],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'product_id']],
            [['warehouse_id'], 'exist', 'skipOnError' => true, 'targetClass' => Warehouse::class, 'targetAttribute' => ['warehouse_id' => 'warehouse_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transaction_item_id' => Yii::t('app', 'Transaction Item ID'),
            'amount' => Yii::t('app', 'Amount'),
            'unit_value' => Yii::t('app', 'Unit Value'),
            'total_value' => Yii::t('app', 'Total Value'),
            'tax_rate' => Yii::t('app', 'Tax Rate'),
            'discount_rate' => Yii::t('app', 'Discount Rate'),
            'product_id' => Yii::t('app', 'Product'),
            'product' => Yii::t('app', 'Product'),
            'warehouse_id' => Yii::t('app', 'Warehouse'),
            'warehouse' => Yii::t('app', 'Warehouse'),
            'company_id' => Yii::t('app', 'Company ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
