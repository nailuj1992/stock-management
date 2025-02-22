<?php

namespace app\models;

use app\models\entities\State;
use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $city_id
 * @property string $code
 * @property string $name
 * @property int $state
 * @property int $country
 */
class CityForm extends \yii\db\ActiveRecord
{
    public $state;
    public $country;

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
            [['code', 'name', 'state', 'country'], 'required'],
            [['state', 'country'], 'integer'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 50],
            [['code'], 'unique'],
            [['state'], 'exist', 'skipOnError' => true, 'targetClass' => State::class, 'targetAttribute' => ['state' => 'state_id']],
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
            'state' => Yii::t(TextConstants::STATE, TextConstants::STATE_MODEL_ID),
            'country' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
        ];
    }
}
