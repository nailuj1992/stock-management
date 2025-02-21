<?php

namespace app\models\entities;

use app\models\TextConstants;
use app\models\Utils;
use Yii;

/**
 * This is the model class for table "transaction_item".
 *
 * @property int $transaction_item_id
 * @property int $transaction_id
 * @property int $amount
 * @property float $unit_value
 * @property float|null $tax_rate
 * @property float|null $discount_rate
 * @property int $product_id
 * @property int|null $warehouse_id
 * @property int $company_id
 * @property string $status
 * @property int|null $created_by
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $updated_at
 *
 * @property Company $company
 * @property Product $product
 * @property Warehouse $warehouse
 */
class TransactionItem extends \yii\db\ActiveRecord
{
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
            [['transaction_id', 'amount', 'unit_value', 'product_id', 'company_id'], 'required'],
            [['transaction_id', 'amount', 'product_id', 'warehouse_id', 'company_id', 'created_by', 'updated_by'], 'integer'],
            [['unit_value', 'tax_rate', 'discount_rate'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 1],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'company_id']],
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
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'amount' => Yii::t('app', 'Amount'),
            'unit_value' => Yii::t('app', 'Unit Value'),
            'tax_rate' => Yii::t('app', 'Tax Rate'),
            'discount_rate' => Yii::t('app', 'Discount Rate'),
            'product_id' => Yii::t(TextConstants::PRODUCT, TextConstants::PRODUCT_MODEL_ID),
            'warehouse_id' => Yii::t(TextConstants::WAREHOUSE, TextConstants::WAREHOUSE_MODEL_ID),
            'company_id' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_ID),
            'status' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
            'created_by' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CREATED_BY),
            'created_at' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CREATED_AT),
            'updated_by' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_UPDATED_BY),
            'updated_at' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_UPDATED_AT),
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::class, ['company_id' => 'company_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['product_id' => 'product_id']);
    }

    /**
     * Gets query for [[Warehouse]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWarehouse()
    {
        return $this->hasOne(Warehouse::class, ['warehouse_id' => 'warehouse_id']);
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
