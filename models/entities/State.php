<?php

namespace app\models\entities;

use app\models\TextConstants;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "state".
 *
 * @property int $state_id
 * @property string $code
 * @property string $name
 * @property int $country_id
 *
 * @property City[] $cities
 * @property Country $country
 */
class State extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'country_id'], 'required'],
            [['country_id'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 50],
            [['code'], 'unique'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::class, 'targetAttribute' => ['country_id' => 'country_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'state_id' => Yii::t(TextConstants::STATE, TextConstants::STATE_MODEL_ID),
            'code' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CODE),
            'name' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_NAME),
            'country_id' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
        ];
    }

    /**
     * Gets query for [[Cities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::class, ['state_id' => 'state_id']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['country_id' => 'country_id']);
    }

    public static function getStates($idCountry)
    {
        $query = self::find()->where(['country_id' => $idCountry])->asArray()->all();
        return ArrayHelper::map($query, 'state_id', 'name');
    }
}
