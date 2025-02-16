<?php

namespace app\controllers;

use app\models\Constants;
use app\models\entities\Document;
use app\models\entities\Product;
use app\models\entities\Supplier;
use app\models\entities\Transaction;
use app\models\entities\TransactionItem;
use app\models\entities\Warehouse;
use app\models\TransactionDto;
use app\models\TransactionItemDto;
use app\models\Utils;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\bootstrap5\Html;

/**
 * TransactionController implements the CRUD actions for Document model.
 */
class TransactionController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'view',
                                'index',
                                'create',
                                'draft',
                                'get-next-num-transaction',
                                'is-document-for-suppliers',
                                'is-document-linked-with-other-transaction',
                                'get-linked-transactions',
                                'document-has-expiration',
                                'document-has-taxes',
                                'get-product-info',
                                'delete-draft',
                            ],
                            'roles' => [Constants::ROLE_USER],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete-draft' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Document models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $dataProvider = new ActiveDataProvider([
            'query' => Transaction::find()
                ->where(['=', 'company_id', $company_id])
                ->andWhere(['IN', 'status', [Constants::STATUS_ACTIVE_DB, Constants::STATUS_INACTIVE_DB, Constants::STATUS_NULL_DB]]),
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'creation_date' => SORT_DESC,
                    'updated_at' => SORT_DESC,
                ]
            ],
        ]);

        $dataProviderDraft = new ActiveDataProvider([
            'query' => Transaction::find()
                ->where(['=', 'company_id', $company_id])
                ->andWhere(['IN', 'status', [Constants::STATUS_DRAFT_DB]]),
            'sort' => [
                'defaultOrder' => [
                    'creation_date' => SORT_DESC,
                    'updated_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderDraft' => $dataProviderDraft,
            'companyId' => $company_id,
        ]);
    }

    /**
     * Displays a single Transaction model.
     * @param int $transaction_id Transaction ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($transaction_id)
    {
        $model = $this->findModel($transaction_id);
        $company_id = $model->company_id;

        if (in_array($model->status, [Constants::STATUS_DRAFT_DB, Constants::STATUS_DELETED_DB])) {
            throw new NotFoundHttpException(Yii::t('app', Constants::MESSAGE_PAGE_NOT_EXISTS));
        }

        $transactionDto = TransactionDto::newTransactionDto($model);
        $document = $model->document;

        $transactionDto->transaction_items = [];
        TransactionDto::getTransactionItems($transaction_id, $company_id, $document, $transactionDto);

        return $this->render('view', [
            'model' => $model,
            'transactionDto' => $transactionDto,
            'document' => $document,
        ]);
    }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'draft' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::belongsToCompany($company_id);
        $model = new TransactionDto();

        $documents = Document::getActiveDocumentsForCompany($company_id);
        $suppliers = Supplier::getActiveSuppliersForCompany($company_id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $user_id = Yii::$app->user->identity->user_id;

                $transaction = new Transaction();

                $transaction->isNewRecord = true;
                $transaction->num_transaction = $model->num_transaction;
                $transaction->document_id = $model->document_id;
                $transaction->creation_date = $model->creation_date;
                $transaction->expiration_date = $model->expiration_date;
                $transaction->linked_transaction_id = $model->linked_transaction_id;
                $transaction->supplier_id = $model->supplier_id;
                $transaction->company_id = $company_id;

                $transaction->status = Constants::STATUS_DRAFT_DB;
                $transaction->created_by = $user_id;
                $transaction->updated_by = $user_id;
                if ($transaction->validate() && $transaction->save()) {
                    return $this->redirect(['draft', 'transaction_id' => $transaction->transaction_id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'documents' => $documents,
            'suppliers' => $suppliers,
        ]);
    }

    public function actionGetLinkedTransactions($document_id)
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $document = Document::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return null;
        }

        $other_transactionsQuery = Transaction::find()->select(['`transaction`.transaction_id', 'concat(`document`.code, \' - \', `transaction`.num_transaction) as num_transaction'])
            ->leftJoin('document', '`document`.document_id = `transaction`.document_id')
            ->where(['=', '`transaction`.company_id', $company_id])
            ->andWhere(['=', '`transaction`.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['=', '`document`.has_other_transaction', Constants::OPTION_NO_DB]);
        if ($document->intended_for === Constants::DOCUMENT_ACTION_INTENDED_INPUT_DB) {
            $other_transactionsQuery = $other_transactionsQuery->andWhere(['=', '`document`.intended_for', Constants::DOCUMENT_ACTION_INTENDED_INPUT_DB]);
        }
        if ($document->intended_for === Constants::DOCUMENT_ACTION_INTENDED_OUTPUT_DB) {
            $other_transactionsQuery = $other_transactionsQuery->andWhere(['=', '`document`.intended_for', Constants::DOCUMENT_ACTION_INTENDED_OUTPUT_DB]);
        }
        $other_transactionsQuery = $other_transactionsQuery->orderBy(['`transaction`.created_at' => SORT_DESC, '`transaction`.num_transaction' => SORT_DESC])
            ->asArray()->all();
        $other_transactions = ArrayHelper::map($other_transactionsQuery, 'transaction_id', 'num_transaction');

        $resp = Html::tag('option', Html::encode(Yii::t('app', 'Select...')), ['value' => '']);
        foreach ($other_transactions as $key => $value) {
            $resp .= Html::tag('option', Html::encode($value), ['value' => $key, 'selected' => false]);
        }
        return $resp;
    }

    public function actionGetNextNumTransaction($document_id)
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $transactions = Transaction::find()
            ->where(['=', 'document_id', $document_id])
            ->andWhere(['=', 'company_id', $company_id])
            ->orderBy(['created_at' => SORT_DESC, 'num_transaction' => SORT_DESC])
            ->asArray()->all();

        $num = 0;
        $length = Constants::MAX_LENGTH_NUM_TRANSACTION;
        if ($transactions && !empty($transactions)) {
            $item = $transactions[0];
            $numTransaction = (int) $item['num_transaction'];
            if ($numTransaction > $num) {
                $num = $numTransaction;
                $length = strlen($item['num_transaction']);
            }

        }
        $num += 1;
        return str_pad($num, $length, '0', STR_PAD_LEFT);
    }

    public function actionIsDocumentForSuppliers($document_id)
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $document = Document::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return false;
        }
        return $document->appliesForSupplier();
    }

    public function actionIsDocumentLinkedWithOtherTransaction($document_id)
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $document = Document::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return false;
        }
        return $document->hasOtherTransaction();
    }

    public function actionDocumentHasExpiration($document_id)
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $document = Document::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return false;
        }
        return $document->hasExpiration();
    }

    /**
     * Creates new TransactionItem[] models.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param mixed $transaction_id
     * @throws \yii\web\NotFoundHttpException
     * @return string|Yii\web\Response
     */
    public function actionDraft($transaction_id)
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::belongsToCompany($company_id);

        $model = $this->findModel($transaction_id);

        if ($model->status !== Constants::STATUS_DRAFT_DB) {
            throw new NotFoundHttpException(Yii::t('app', Constants::MESSAGE_PAGE_NOT_EXISTS));
        }

        $transactionDto = TransactionDto::newTransactionDto($model);
        $document = $model->document;
        $products = Product::getActiveProductsForCompany($company_id);
        $warehouses = Warehouse::getActiveWarehousesForCompany($company_id);

        if (isset($this->request->post()['TransactionItemDto'])) {
            $this->regatherTransactionItemsDraft($document, $transactionDto, $this->request->post()['TransactionItemDto']);
        } elseif (isset($transactionDto->linked_transaction_id)) {
            TransactionDto::getTransactionItems($transactionDto->linked_transaction_id, $company_id, $document, $transactionDto, Constants::STATUS_ACTIVE_DB);
        } else {
            $this->createNewTransactionItemsDraft($transactionDto);
        }

        if (Yii::$app->request->post('addRow') == 'true') {
            $this->actionAddRowDraft($transactionDto);
        }

        if (
            str_contains(Yii::$app->request->post('removeRow'), 'row-')
            && isset($transactionDto->transaction_items) && !empty($transactionDto->transaction_items)
        ) {
            $this->actionRemoveRowDraft(Yii::$app->request->post('removeRow'), $document, $transactionDto);
        }

        if (
            $this->request->isPost && Yii::$app->request->post('save') == 'true'
            && isset($this->request->post()['TransactionItemDto'])
        ) {
            $transactionDto->transaction_items = $this->request->post()['TransactionItemDto'];

            $errors = 0;
            if (!empty($transactionDto->transaction_items)) {
                foreach ($transactionDto->transaction_items as $transactionItemDto) {
                    // TODO Add existences validation only for Output transactions.

                    // if ($transactionItemDto->amount === '' || $transactionItemDto->amount === '0') {
                    //     $transactionItemDto->addError('amount', Yii::t('app', 'Amount should be greater than 0.'));
                    //     $errors++;
                    // }
                }
            }

            if ($errors === 0 && !empty($transactionDto->transaction_items)) {
                $saved = $this->actionSaveDraft($model, $transactionDto, $company_id);
                if ($saved) {
                    return $this->redirect(['view', 'transaction_id' => $model->transaction_id]);
                }
            }
        }

        return $this->render('draft', [
            'model' => $model,
            'transactionDto' => $transactionDto,
            'document' => $document,
            'products' => $products,
            'warehouses' => $warehouses,
        ]);
    }

    public function actionDocumentHasTaxes($document_id)
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $document = Document::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return false;
        }
        return $document->hasTaxes();
    }

    /**
     * To regather the transaction items after clicking any submit button.
     * @param \app\models\entities\Document $document
     * @param \app\models\TransactionDto $transactionDto
     * @param mixed $transactionItems
     * @return void
     */
    private function regatherTransactionItemsDraft(Document $document, TransactionDto $transactionDto, $transactionItems): void
    {
        $transactionDto->transaction_items = [];

        $subtotal = 0;
        $taxes = 0;
        foreach ($transactionItems as $transactionItem) {
            $transactionItemDto = new TransactionItemDto();
            $transactionItemDto->product_id = $transactionItem['product_id'];
            $transactionItemDto->warehouse_id = $transactionItem['warehouse_id'];
            $transactionItemDto->amount = $transactionItem['amount'];
            $transactionItemDto->unit_value = $transactionItem['unit_value'];
            $transactionItemDto->discount_rate = $transactionItem['discount_rate'];
            $transactionItemDto->total_value = TransactionDto::calculateTotalValueProduct($transactionItemDto->amount, $transactionItemDto->unit_value, $transactionItemDto->discount_rate);

            $info = $this->actionGetProductInfo($transactionItemDto->product_id, $transactionItemDto->warehouse_id);
            if (isset($info)) {
                $infoMap = json_decode($info);
                if (isset($infoMap->{'taxRate'})) {
                    $transactionItemDto->tax_rate = $infoMap->{'taxRate'};
                }
            }
            $transactionDto->transaction_items[] = $transactionItemDto;

            $subtotal += $transactionItemDto->total_value;
            if ($document->hasTaxes() && isset($transactionItemDto->tax_rate)) {
                $valueTaxes = $subtotal * ($transactionItemDto->tax_rate / 100);
                $taxes += $valueTaxes;
            }
            $total = $subtotal + $taxes;

            $transactionDto->total_before_taxes = $subtotal;
            $transactionDto->total_taxes = $taxes;
            $transactionDto->total_value = $total;
        }
    }

    /**
     * To initialize a new array of transaction items if none of the above conditions were accomplished.
     * @param \app\models\TransactionDto $transactionDto
     * @return void
     */
    private function createNewTransactionItemsDraft(TransactionDto $transactionDto): void
    {
        $transactionDto->transaction_items = [];
        $transactionDto->transaction_items[] = new TransactionItemDto();
    }

    /**
     * To manage the action of the Add item submit button.
     * @param \app\models\TransactionDto $transactionDto
     * @return void
     */
    private function actionAddRowDraft(TransactionDto $transactionDto): void
    {
        $transactionDto->transaction_items[] = new TransactionItemDto();
    }

    /**
     * To manage the action of the Remove item submit buttons.
     * @param mixed $field
     * @param \app\models\entities\Document $document
     * @param \app\models\TransactionDto $transactionDto
     * @return void
     */
    private function actionRemoveRowDraft($field, Document $document, TransactionDto $transactionDto): void
    {
        $i = (int) explode('row-', $field)[1];
        array_splice($transactionDto->transaction_items, $i, 1);

        $subtotal = 0;
        $taxes = 0;

        foreach ($transactionDto->transaction_items as $transactionItemDto) {
            $subtotal += $transactionItemDto->total_value;
            if ($document->hasTaxes() && isset($transactionItemDto->tax_rate)) {
                $valueTaxes = $subtotal * ($transactionItemDto->tax_rate / 100);
                $taxes += $valueTaxes;
            }
            $total = $subtotal + $taxes;
        }

        $transactionDto->total_before_taxes = $subtotal;
        $transactionDto->total_taxes = $taxes;
        $transactionDto->total_value = $total;
    }

    /**
     * To manage the action of the Save submit button.
     * @param \app\models\entities\Transaction $model
     * @param \app\models\TransactionDto $transactionDto
     * @param mixed $company_id
     * @return bool If the creation of the transaction items was successful.
     */
    private function actionSaveDraft(Transaction $model, TransactionDto $transactionDto, $company_id): bool
    {
        $user_id = Yii::$app->user->identity->user_id;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $success = true;
            foreach ($transactionDto->transaction_items as $transactionItemDto) {
                $transactionItem = new TransactionItem();

                $transactionItem->isNewRecord = true;
                $transactionItem->transaction_id = $model->transaction_id;
                $transactionItem->product_id = $transactionItemDto['product_id'];
                $transactionItem->warehouse_id = isset($transactionItemDto['warehouse_id']) && $transactionItemDto['warehouse_id'] !== '' ? $transactionItemDto['warehouse_id'] : null;
                $transactionItem->amount = $transactionItemDto['amount'];
                $transactionItem->unit_value = $transactionItemDto['unit_value'];
                $transactionItem->tax_rate = isset($transactionItemDto['tax_rate']) && $transactionItemDto['tax_rate'] !== '' ? $transactionItemDto['tax_rate'] : null;
                $transactionItem->discount_rate = isset($transactionItemDto['discount_rate']) && $transactionItemDto['discount_rate'] !== '' ? $transactionItemDto['discount_rate'] : null;
                $transactionItem->company_id = $company_id;
                $transactionItem->status = Constants::STATUS_ACTIVE_DB;
                $transactionItem->created_by = $user_id;
                $transactionItem->updated_by = $user_id;

                $success = $success && $transactionItem->validate() && $transactionItem->save();
            }

            $model->status = Constants::STATUS_ACTIVE_DB;
            $model->updated_by = $user_id;
            $model->updated_at = Utils::getDateNowDB();

            $success = $success && $model->validate() && $model->save();

            if ($success) {
                $transaction->commit();
                return true;
            }
            $transaction->rollBack();
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
        return false;
    }

    public function actionGetProductInfo($document_id, $product_id, $warehouse_id = '')
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $document = Document::findOne(['document_id' => $document_id]);
        if ($document === null) {
            return null;
        }
        $product = Product::findOne(['product_id' => $product_id]);
        if ($product === null) {
            return null;
        }
        if ($warehouse_id === '') {
            $warehouse_id = null;
        }
        if ($document->intended_for === Constants::DOCUMENT_ACTION_INTENDED_OUTPUT_DB) {
            if (isset($warehouse_id)) {
                $warehouse = Warehouse::findOne(['warehouse_id' => $warehouse_id]);
            }
            $value = $product->sugested_value;// TODO Await for kardex implementation
            $taxRate = $product->tax_rate;
            $discountRate = $product->discount_rate;
        } else {
            $value = '';
            $taxRate = '';
            $discountRate = '';
        }
        return json_encode([
            'value' => $value,
            'taxRate' => $taxRate,
            'discountRate' => $discountRate,
        ]);
    }

    /**
     * Deletes an existing draft Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $transaction_id Transaction ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteDraft($transaction_id)
    {
        $model = $this->findModel($transaction_id);
        $user_id = Yii::$app->user->identity->user_id;

        if ($model->status !== Constants::STATUS_DRAFT_DB) {
            throw new ForbiddenHttpException(Yii::t('app', Constants::MESSAGE_INFO_DELETED_NOT_DRAFT_TRANSACTION));
        }

        $model->status = Constants::STATUS_DELETED_DB;
        $model->updated_by = $user_id;
        $model->updated_at = Utils::getDateNowDB();

        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $transaction_id Transaction ID
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($transaction_id)
    {
        $model = Transaction::findOne(['transaction_id' => $transaction_id]);

        if ($model !== null) {
            Utils::validateCompanySelected();
            Utils::validateBelongsToCompany($model->company_id);
            Utils::validateCompanyMatches($model->company_id);
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', Constants::MESSAGE_PAGE_NOT_EXISTS));
    }
}