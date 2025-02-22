<?php

namespace app\models;

use app\models\entities\City;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $email
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $role
 * @property int $country
 * @property int $state
 * @property int $city
 */
class UserCompanyForm extends \yii\db\ActiveRecord
{
    public $email;
    public $name;
    public $phone;
    public $address;
    public $country;
    public $state;
    public $city;
    public $role;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'phone', 'address', 'role', 'country', 'state', 'city'], 'required'],
            [['city', 'state', 'country'], 'integer'],
            [['email', 'address'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 13],
            [['phone'], 'validatePhone', 'message' => Yii::t(TextConstants::APP, TextConstants::MESSAGE_VALIDATE_PHONE)],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['city'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => ['city' => 'city_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'companyId' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_ID),
            'email' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_EMAIL),
            'name' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_NAME),
            'phone' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_PHONE),
            'address' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_ADDRESS),
            'role' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_ROLE),
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
