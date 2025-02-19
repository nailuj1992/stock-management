<?php

namespace app\models\entities;

use app\models\TextConstants;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "country".
 *
 * @property int $country_id
 * @property string $code
 * @property string $name
 *
 * @property State[] $states
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code'], 'string', 'max' => 10],
            [['name'], 'string', 'max' => 50],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'country_id' => Yii::t(TextConstants::COUNTRY, TextConstants::COUNTRY_MODEL_ID),
            'code' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CODE),
            'name' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_NAME),
        ];
    }

    /**
     * Gets query for [[States]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStates()
    {
        return $this->hasMany(State::class, ['country_id' => 'country_id']);
    }

    public static function getCountries()
    {
        $query = self::find()->asArray()->all();
        return ArrayHelper::map($query, 'country_id', 'name');
    }
}
