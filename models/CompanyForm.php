<?php

namespace app\models;

use Yii;
use app\models\entities\City;

/**
 * This is the model class for table "company".
 *
 * @property string $company_id
 * @property string $code
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property int $country
 * @property int $state
 * @property int $city
 */
class CompanyForm extends \yii\db\ActiveRecord
{
    public $code;
    public $name;
    public $phone;
    public $address;
    public $country;
    public $state;
    public $city;

    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'phone', 'address', 'country', 'state', 'city'], 'required'],
            [['city', 'state', 'country'], 'integer'],
            [['address'], 'string', 'max' => 100],
            [['code'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 13],
            [['phone'], 'validatePhone', 'message' => Yii::t(TextConstants::APP, TextConstants::MESSAGE_VALIDATE_PHONE)],
            [['code'], 'unique'],
            [['city'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city' => 'city_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_CODE),
            'name' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_NAME),
            'phone' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_PHONE),
            'address' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_ADDRESS),
            'country' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
            'state' => Yii::t(TextConstants::STATE, TextConstants::STATE_MODEL_ID),
            'city' => Yii::t(TextConstants::CITY, TextConstants::CITY_MODEL_ID),
        ];
    }

    public function validatePhone($attribute)
    {
        return Utils::validatePhone($attribute);
    }
}
