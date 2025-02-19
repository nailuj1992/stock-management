<?php

namespace app\models;
use app\models\entities\City;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $email
 * @property string $password
 * @property string $repassword
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property int $country
 * @property int $state
 * @property int $city
 */
class UserSignUp extends \yii\db\ActiveRecord
{
    public $email;
    public $password;
    public $repassword;
    public $name;
    public $phone;
    public $address;
    public $country;
    public $state;
    public $city;

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
            [['email', 'password', 'repassword', 'name', 'phone', 'address', 'country', 'state', 'city'], 'required'],
            [['city', 'state', 'country'], 'integer'],
            [['email', 'password', 'repassword', 'address'], 'string', 'max' => 100],
            [
                'password',
                'match',
                'pattern' => '/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/',
                'message' => Yii::t(TextConstants::APP, TextConstants::MESSAGE_PASSWORD_VALIDATION)
            ],
            [
                'repassword',
                'compare',
                'compareAttribute' => 'password',
                'message' => Yii::t(TextConstants::APP, TextConstants::MESSAGE_PASSWORDS_NOT_MATCH),
            ],
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
            'email' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_EMAIL),
            'password' => Yii::t(TextConstants::USER, TextConstants::USER_MODEL_PASSWORD),
            'repassword' => Yii::t(TextConstants::USER, TextConstants::USER_MODEL_REPASSWORD),
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
