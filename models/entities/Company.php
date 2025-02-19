<?php

namespace app\models\entities;

use app\models\Constants;
use app\models\TextConstants;
use app\models\Utils;
use Yii;
use yii\db\conditions\NotCondition;
use yii\db\Query;

/**
 * This is the model class for table "company".
 *
 * @property int $company_id
 * @property string $code
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
 * @property City $city0
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
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
            [['code', 'name', 'phone', 'address', 'city', 'status'], 'required'],
            [['city', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['code', 'phone'], 'string', 'max' => 20],
            [['name', 'address'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 1],
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
            'company_id' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_ID),
            'code' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_CODE),
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

    public function getFullStatus()
    {
        return Utils::getFullStatus($this->status);
    }

    public static function queryGetCompaniesNotBelongUser()
    {
        $user_id = Yii::$app->user->identity->user_id;
        $subQuery = (new Query())->select('company_id')->from('user_company')->where(['=', 'user_id', $user_id])->andWhere(['=', 'status', Constants::STATUS_ACTIVE_DB]);
        return self::find()
            ->where(['=', 'status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(new NotCondition(['IN', 'company_id', $subQuery]));
    }

    public static function findCompany($company_id)
    {
        return self::find()->where(['=', 'company_id', $company_id])->one();
    }
}
