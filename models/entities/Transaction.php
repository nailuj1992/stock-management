<?php

namespace app\models\entities;

use app\models\Constants;
use app\models\TextConstants;
use app\models\Utils;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "transaction".
 *
 * @property int $transaction_id
 * @property string $num_transaction
 * @property int $document_id
 * @property string $creation_date
 * @property string|null $expiration_date
 * @property int|null $linked_transaction_id
 * @property int|null $supplier_id
 * @property int $company_id
 * @property string $status
 * @property int|null $created_by
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $updated_at
 *
 * @property Company $company
 * @property Document $document
 * @property Transaction $linkedTransaction
 * @property Supplier $supplier
 * @property Transaction[] $transactions
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['num_transaction', 'document_id', 'creation_date', 'company_id'], 'required'],
            [['document_id', 'linked_transaction_id', 'supplier_id', 'company_id', 'created_by', 'updated_by'], 'integer'],
            [['creation_date', 'expiration_date', 'created_at', 'updated_at'], 'safe'],
            [['num_transaction'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 1],
            [['num_transaction', 'document_id'], 'unique', 'targetAttribute' => ['num_transaction', 'document_id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'company_id']],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::class, 'targetAttribute' => ['document_id' => 'document_id']],
            [['linked_transaction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Transaction::class, 'targetAttribute' => ['linked_transaction_id' => 'transaction_id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplier_id' => 'supplier_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transaction_id' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_ID),
            'num_transaction' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_NUM_ID),
            'document_id' => Yii::t(TextConstants::DOCUMENT, TextConstants::DOCUMENT_MODEL_ID),
            'creation_date' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_CREATION_DATE),
            'expiration_date' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_EXPIRATION_DATE),
            'linked_transaction_id' => Yii::t(TextConstants::TRANSACTION, TextConstants::TRANSACTION_MODEL_LINKED),
            'supplier_id' => Yii::t(TextConstants::SUPPLIER, TextConstants::SUPPLIER_MODEL_ID),
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
     * Gets query for [[Document]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::class, ['document_id' => 'document_id']);
    }

    /**
     * Gets query for [[LinkedTransaction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinkedTransaction()
    {
        return $this->hasOne(Transaction::class, ['transaction_id' => 'linked_transaction_id']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::class, ['supplier_id' => 'supplier_id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['linked_transaction_id' => 'transaction_id']);
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

    public static function getLinkedTransactions($document_id, $company_id)
    {
        $document = Document::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return null;
        }

        $other_transactionsQuery = self::find()->select(['`transaction`.transaction_id', 'concat(`document`.code, \' - \', `transaction`.num_transaction) as num_transaction'])
            ->leftJoin('document', '`document`.document_id = `transaction`.document_id')
            ->where(['=', '`transaction`.company_id', $company_id])
            ->andWhere(['=', '`transaction`.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['=', '`document`.has_other_transaction', Constants::OPTION_NO_DB]);
        if ($document->isIntendedForInput()) {
            $other_transactionsQuery = $other_transactionsQuery->andWhere(['=', '`document`.intended_for', Constants::DOCUMENT_ACTION_INTENDED_OUTPUT_DB]);
        }
        if ($document->isIntendedForOutput()) {
            $other_transactionsQuery = $other_transactionsQuery->andWhere(['=', '`document`.intended_for', Constants::DOCUMENT_ACTION_INTENDED_INPUT_DB]);
        }
        if ($document->appliesForSupplier()) {
            $other_transactionsQuery = $other_transactionsQuery->andWhere(['=', '`document`.apply_for', Constants::DOCUMENT_APPLY_SUPPLIER_DB]);
        }
        if ($document->appliesForCustomer()) {
            $other_transactionsQuery = $other_transactionsQuery->andWhere(['=', '`document`.apply_for', Constants::DOCUMENT_APPLY_CUSTOMER_DB]);
        }
        $other_transactionsQuery = $other_transactionsQuery->orderBy(['`transaction`.created_at' => SORT_DESC, '`transaction`.num_transaction' => SORT_DESC])
            ->asArray()->all();
        return ArrayHelper::map($other_transactionsQuery, 'transaction_id', 'num_transaction');
    }

    public static function getSupplierOnTransaction($transaction_id)
    {
        $transaction = self::findOne(['transaction_id' => $transaction_id]);
        if ($transaction === null) {
            return null;
        }
        return $transaction->supplier_id;
    }

    public static function companyHasTransactions($company_id)
    {
        $sql = "SELECT COUNT(transaction_id) countTransactions FROM transaction WHERE company_id = :companyId AND status NOT IN (:status)";

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql, [
            ':companyId' => $company_id,
            ':status' => Constants::STATUS_DELETED_DB,
        ]);
        $result = $command->queryAll();

        if (!isset($result) || empty($result)) {
            return false;
        }
        return $result[0]['countTransactions'] > 0;
    }
}
