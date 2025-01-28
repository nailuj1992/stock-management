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
            'city_id' => Yii::t('app', 'City ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'state' => Yii::t('app', 'State'),
            'country' => Yii::t('app', 'Country'),
        ];
    }
}
