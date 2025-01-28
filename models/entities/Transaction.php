<?php

namespace app\models\entities;

use Yii;

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
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'num_transaction' => Yii::t('app', 'Num Transaction'),
            'document_id' => Yii::t('app', 'Document ID'),
            'creation_date' => Yii::t('app', 'Creation Date'),
            'expiration_date' => Yii::t('app', 'Expiration Date'),
            'linked_transaction_id' => Yii::t('app', 'Linked Transaction ID'),
            'supplier_id' => Yii::t('app', 'Supplier ID'),
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
}
