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
                'message' => Yii::t('app', Constants::MESSAGE_PASSWORD_VALIDATION)
            ],
            [
                'repassword',
                'compare',
                'compareAttribute' => 'password',
                'message' => Yii::t('app', Constants::MESSAGE_PASSWORDS_NOT_MATCH),
            ],
            [['name'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 13],
            [['phone'], 'validatePhone', 'message' => Yii::t('app', Constants::MESSAGE_VALIDATE_PHONE)],
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
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'repassword' => Yii::t('app', 'Re-Password'),
            'name' => Yii::t('app', 'Name'),
            'phone' => Yii::t('app', 'Phone'),
            'address' => Yii::t('app', 'Address'),
            'country' => Yii::t('app', 'Country'),
            'state' => Yii::t('app', 'State'),
            'city' => Yii::t('app', 'City'),
        ];
    }

    public function validatePhone($attribute)
    {
        return Utils::validatePhone($attribute);
    }
}
