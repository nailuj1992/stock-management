<?php

namespace app\models\entities;

use app\models\Constants;
use app\models\Utils;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "warehouse".
 *
 * @property int $warehouse_id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property int $city
 * @property int $company_id
 * @property string $status
 * @property int|null $created_by
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $updated_at
 *
 * @property City $city0
 * @property Company $company
 * @property TransactionItem[] $transactionItems
 */
class Warehouse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'warehouse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'address', 'city', 'company_id'], 'required'],
            [['city', 'company_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 1],
            [['code', 'company_id'], 'unique', 'targetAttribute' => ['code', 'company_id']],
            [['city'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city' => 'city_id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'company_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'warehouse_id' => Yii::t('app', 'Warehouse ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'address' => Yii::t('app', 'Address'),
            'city' => Yii::t('app', 'City'),
            'company_id' => Yii::t('app', 'Company ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[City0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity0()
    {
        return $this->hasOne(City::class, ['city_id' => 'city']);
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
        return $this->hasMany(TransactionItem::class, ['warehouse_id' => 'warehouse_id']);
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

    private static function getActiveWarehousesForCompanyQuery($company_id)
    {
        return self::find()->select(['warehouse_id', 'concat(code, \' - \', name) as name'])
            ->where(['=', 'company_id', $company_id])
            ->andWhere(['=', 'status', Constants::STATUS_ACTIVE_DB])
            ->asArray()->all();
    }

    public static function getActiveWarehousesForCompany($company_id)
    {
        $warehouses = self::getActiveWarehousesForCompanyQuery($company_id);
        return ArrayHelper::map($warehouses, 'warehouse_id', 'name');
    }

    public static function getActiveWarehousesForCompanyOrAll($company_id)
    {
        $warehouses = self::getActiveWarehousesForCompanyQuery($company_id);
        $warehouses[] = ['warehouse_id' => Constants::OPTION_ALL, 'name' => Yii::t('app', 'All')];
        return ArrayHelper::map($warehouses, 'warehouse_id', 'name');
    }
}
