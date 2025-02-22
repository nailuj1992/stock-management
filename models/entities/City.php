<?php

namespace app\models\entities;

use app\models\TextConstants;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "city".
 *
 * @property int $city_id
 * @property string $code
 * @property string $name
 * @property int $state_id
 *
 * @property Company[] $companies
 * @property State $state
 * @property User[] $users
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'state_id'], 'required'],
            [['state_id'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 50],
            [['code'], 'unique'],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::class, 'targetAttribute' => ['state_id' => 'state_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'city_id' => Yii::t(TextConstants::CITY, TextConstants::CITY_MODEL_ID),
            'code' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CODE),
            'name' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_NAME),
            'state_id' => Yii::t(TextConstants::STATE, TextConstants::STATE_MODEL_ID),
        ];
    }

    /**
     * Gets query for [[Companies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::class, ['city' => 'city_id']);
    }

    /**
     * Gets query for [[State]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::class, ['state_id' => 'state_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['city' => 'city_id']);
    }

    public static function getCities($idState)
    {
        $query = self::find()->where(['state_id' => $idState])->asArray()->all();
        return ArrayHelper::map($query, 'city_id', 'name');
    }
}
