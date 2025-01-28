<?php

namespace app\models;

use app\models\entities\City;
use app\models\entities\Company;
use Yii;

/**
 * This is the model class for table "warehouse".
 *
 * @property int $warehouse_id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property int $country
 * @property int $state
 * @property int $city
 * @property int $company_id
 */
class WarehouseEdit extends \yii\db\ActiveRecord
{
    public $code;
    public $name;
    public $address;
    public $country;
    public $state;
    public $city;
    public $company_id;

    public static function tableName()
    {
        return 'warehouse';
    }

    public function rules()
    {
        return [
            [['warehouse_id', 'code', 'name', 'address', 'country', 'state', 'city', 'company_id'], 'required'],
            [['city', 'state', 'country', 'company_id'], 'integer'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 100],
            [['code', 'company_id'], 'unique', 'targetAttribute' => ['code', 'company_id']],
            [['city'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city' => 'city_id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'company_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'warehouse_id' => Yii::t('app', 'Warehouse ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'address' => Yii::t('app', 'Address'),
            'country' => Yii::t('app', 'Country'),
            'state' => Yii::t('app', 'State'),
            'city' => Yii::t('app', 'City'),
            'company_id' => Yii::t('app', 'Company ID'),
        ];
    }
}