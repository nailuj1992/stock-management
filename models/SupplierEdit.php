<?php

namespace app\models;

use app\models\entities\City;
use app\models\entities\Company;
use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property int $supplier_id
 * @property string $code
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property int $country
 * @property int $state
 * @property int $city
 * @property int $company_id
 */
class SupplierEdit extends \yii\db\ActiveRecord
{
    public $code;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $country;
    public $state;
    public $city;
    public $company_id;

    public static function tableName()
    {
        return 'supplier';
    }

    public function rules()
    {
        return [
            [['supplier_id', 'code', 'name', 'email', 'phone', 'address', 'country', 'state', 'city', 'company_id'], 'required'],
            [['city', 'state', 'country', 'company_id'], 'integer'],
            [['code'], 'string', 'max' => 20],
            [['name', 'email', 'address'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 13],
            [['phone'], 'validatePhone', 'message' => Yii::t('app', Constants::MESSAGE_VALIDATE_PHONE)],
            [['email'], 'email'],
            [['code', 'company_id'], 'unique', 'targetAttribute' => ['code', 'company_id']],
            [['city'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city' => 'city_id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'company_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'supplier_id' => Yii::t('app', 'Supplier ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'address' => Yii::t('app', 'Address'),
            'country' => Yii::t('app', 'Country'),
            'state' => Yii::t('app', 'State'),
            'city' => Yii::t('app', 'City'),
            'company_id' => Yii::t('app', 'Company ID'),
        ];
    }

    public function validatePhone($attribute)
    {
        return Utils::validatePhone($attribute);
    }
}