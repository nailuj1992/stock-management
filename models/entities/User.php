<?php

namespace app\models\entities;

use app\models\TextConstants;
use app\models\Utils;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property string $email
 * @property string $password
 * @property string|null $auth_key
 * @property string|null $access_token
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property int $city
 * @property string $status
 * @property int|null $created_by
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property City $city0
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
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
            [['email', 'password', 'name', 'phone', 'address', 'city', 'status'], 'required'],
            [['city', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['email', 'password', 'address'], 'string', 'max' => 100],
            [['auth_key', 'access_token'], 'string', 'max' => 70],
            [['name'], 'string', 'max' => 200],
            [['phone'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 1],
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
            'user_id' => Yii::t(TextConstants::USER, TextConstants::USER_MODEL_ID),
            'email' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_EMAIL),
            'password' => Yii::t(TextConstants::USER, TextConstants::USER_MODEL_PASSWORD),
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'name' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_NAME),
            'phone' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_PHONE),
            'address' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_ADDRESS),
            'city' => Yii::t(TextConstants::CITY, TextConstants::CITY_MODEL_ID),
            'status' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
            'created_by' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CREATED_BY),
            'created_at' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CREATED_AT),
            'updated_by' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_UPDATED_BY),
            'updated_at' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_UPDATED_AT),
        ];
    }

    /**
     * Gets query for [[AuthAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['user_id' => 'user_id']);
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
