<?php

namespace app\models\entities;

use app\models\Constants;
use app\models\Utils;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "document".
 *
 * @property int $document_id
 * @property string $code
 * @property string $name
 * @property string $intended_for
 * @property string $apply_for
 * @property string $has_taxes
 * @property string $has_expiration
 * @property string $has_other_transaction
 * @property int $company_id
 * @property string $status
 * @property int|null $created_by
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property int|null $updated_at
 *
 * @property Company $company
 * @property Transaction[] $transactions
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'intended_for', 'apply_for', 'has_taxes', 'has_expiration', 'has_other_transaction', 'company_id'], 'required'],
            [['company_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 50],
            [['intended_for', 'apply_for', 'has_taxes', 'has_expiration', 'has_other_transaction', 'status'], 'string', 'max' => 1],
            [['code', 'company_id'], 'unique', 'targetAttribute' => ['code', 'company_id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'company_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'document_id' => Yii::t('app', 'Document ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
            'intended_for' => Yii::t('app', 'Intended For'),
            'apply_for' => Yii::t('app', 'Apply For'),
            'has_taxes' => Yii::t('app', 'Has Taxes'),
            'has_expiration' => Yii::t('app', 'Has Expiration'),
            'has_other_transaction' => Yii::t('app', 'Applies over other transaction?'),
            'company_id' => Yii::t('app', 'Company ID'),
            'status' => Yii::t('app', 'Status'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::class, ['company_id' => 'company_id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['document_id' => 'document_id']);
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

    public function getFullAction()
    {
        return Utils::getFullAction($this->intended_for);
    }

    public function getFullApply()
    {
        return Utils::getFullApply($this->apply_for);
    }

    public function getFullTaxes()
    {
        return Utils::getFullYesNo($this->has_taxes);
    }

    public function getFullExpiration()
    {
        return Utils::getFullYesNo($this->has_expiration);
    }

    public function getFullOtherTransaction()
    {
        return Utils::getFullYesNo($this->has_other_transaction);
    }

    public static function getActionsIntendedFor()
    {
        $resp = [
            [
                'code' => Constants::DOCUMENT_ACTION_INTENDED_INPUT_DB,
                'name' => Yii::t('app', Constants::DOCUMENT_ACTION_INTENDED_INPUT)
            ],
            [
                'code' => Constants::DOCUMENT_ACTION_INTENDED_OUTPUT_DB,
                'name' => Yii::t('app', Constants::DOCUMENT_ACTION_INTENDED_OUTPUT)
            ]
        ];
        return ArrayHelper::map($resp, 'code', 'name');
    }

    public static function getPeopleApplyFor()
    {
        $resp = [
            [
                'code' => Constants::DOCUMENT_APPLY_SUPPLIER_DB,
                'name' => Yii::t('app', Constants::DOCUMENT_APPLY_SUPPLIER)
            ],
            [
                'code' => Constants::DOCUMENT_APPLY_CUSTOMER_DB,
                'name' => Yii::t('app', Constants::DOCUMENT_APPLY_CUSTOMER)
            ]
        ];
        return ArrayHelper::map($resp, 'code', 'name');
    }
}
