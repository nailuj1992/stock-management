<?php

namespace app\models\entities;

use app\models\Constants;
use app\models\TextConstants;
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
            'document_id' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_ID),
            'code' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CODE),
            'name' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_NAME),
            'intended_for' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_INTENDED_FOR),
            'apply_for' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_APPLY_FOR),
            'has_taxes' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_HAS_TAXES),
            'has_expiration' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_HAS_EXPIRATION),
            'has_other_transaction' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_HAS_OTHER_TRANSACTION),
            'company_id' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_ID),
            'status' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
            'created_by' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CREATED_BY),
            'created_at' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CREATED_AT),
            'updated_by' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_UPDATED_BY),
            'updated_at' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_UPDATED_AT),
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

    public function appliesForSupplier()
    {
        return $this->apply_for === Constants::DOCUMENT_APPLY_SUPPLIER_DB;
    }

    public function appliesForCustomer()
    {
        return $this->apply_for === Constants::DOCUMENT_APPLY_CUSTOMER_DB;
    }

    public function hasOtherTransaction()
    {
        return $this->has_other_transaction === Constants::OPTION_YES_DB;
    }

    public function hasExpiration()
    {
        return $this->has_expiration === Constants::OPTION_YES_DB;
    }

    public function hasTaxes()
    {
        return $this->has_taxes === Constants::OPTION_YES_DB;
    }

    public function isIntendedForOutput()
    {
        return $this->intended_for === Constants::DOCUMENT_ACTION_INTENDED_OUTPUT_DB;
    }

    public function isIntendedForInput()
    {
        return $this->intended_for === Constants::DOCUMENT_ACTION_INTENDED_INPUT_DB;
    }

    public static function getActionsIntendedFor()
    {
        $resp = [
            [
                'code' => Constants::DOCUMENT_ACTION_INTENDED_INPUT_DB,
                'name' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_ACTION_INTENDED_INPUT)
            ],
            [
                'code' => Constants::DOCUMENT_ACTION_INTENDED_OUTPUT_DB,
                'name' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_ACTION_INTENDED_OUTPUT)
            ]
        ];
        return ArrayHelper::map($resp, 'code', 'name');
    }

    public static function getPeopleApplyFor()
    {
        $resp = [
            [
                'code' => Constants::DOCUMENT_APPLY_SUPPLIER_DB,
                'name' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_APPLY_SUPPLIER)
            ],
            [
                'code' => Constants::DOCUMENT_APPLY_CUSTOMER_DB,
                'name' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_APPLY_CUSTOMER)
            ]
        ];
        return ArrayHelper::map($resp, 'code', 'name');
    }

    public static function getActiveDocumentsForCompany($company_id)
    {
        $documents = self::find()->select(['document_id', 'concat(code, \' - \', name) as name'])
            ->where(['=', 'company_id', $company_id])
            ->andWhere(['=', 'status', Constants::STATUS_ACTIVE_DB])
            ->asArray()->all();
        return ArrayHelper::map($documents, 'document_id', 'name');
    }

    public static function isDocumentForSuppliers($document_id)
    {
        $document = self::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return false;
        }
        return $document->appliesForSupplier();
    }

    public static function isDocumentLinkedWithOtherTransaction($document_id)
    {
        $document = self::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return false;
        }
        return $document->hasOtherTransaction();
    }

    public static function documentHasExpiration($document_id)
    {
        $document = self::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return false;
        }
        return $document->hasExpiration();
    }

    public static function documentHasTaxes($document_id)
    {
        $document = self::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return false;
        }
        return $document->hasTaxes();
    }
}
