<?php

namespace app\models\entities;

use app\models\Constants;
use app\models\Utils;
use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $product_id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string $has_existences
 * @property float|null $tax_rate
 * @property int|null $minimum_stock
 * @property int|null $sugested_value
 * @property int $company_id
 * @property string $status
 * @property int|null $created_by
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $updated_at
 *
 * @property Company $company
 * @property TransactionItem[] $transactionItems
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'has_existences', 'company_id'], 'required'],
            [['tax_rate'], 'number'],
            [['minimum_stock', 'sugested_value', 'company_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 100],
            [['has_existences', 'status'], 'string', 'max' => 1],
            [['code', 'company_id'], 'unique', 'targetAttribute' => ['code', 'company_id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'company_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'Product ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'has_existences' => Yii::t('app', 'Has Existences?'),
            'tax_rate' => Yii::t('app', 'Tax Rate'),
            'minimum_stock' => Yii::t('app', 'Minimum Stock'),
            'sugested_value' => Yii::t('app', 'Suggested Value'),
            'company_id' => Yii::t('app', 'Company ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
     * Gets query for [[TransactionItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionItems()
    {
        return $this->hasMany(TransactionItem::class, ['product_id' => 'product_id']);
    }

    public function hasExistences()
    {
        return $this->has_existences === Constants::OPTION_YES_DB;
    }

    public function getFullExistences()
    {
        return Utils::getFullYesNo($this->has_existences);
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
