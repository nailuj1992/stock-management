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
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
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
                            'actions' => ['view', 'index', 'create', 'draft', 'get-next-num-transaction', 'is-document-for-suppliers', 'is-document-linked-with-other-transaction', 'get-linked-transactions', 'document-has-expiration', 'get-product-info'],
                            'roles' => [Constants::ROLE_USER],
                        ],
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
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $dataProviderDraft = new ActiveDataProvider([
            'query' => Transaction::find()
                ->where(['=', 'company_id', $company_id])
                ->andWhere(['IN', 'status', [Constants::STATUS_DRAFT_DB]]),
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
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
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::belongsToCompany($company_id);
        $model = new TransactionDto();

        $documentsQuery = Document::find()->select(['document_id', 'concat(code, \' - \', name) as name'])
            ->where(['=', 'company_id', $company_id])
            ->andWhere(['=', 'status', Constants::STATUS_ACTIVE_DB])
            ->asArray()->all();
        $documents = ArrayHelper::map($documentsQuery, 'document_id', 'name');

        $suppliersQuery = Supplier::find()->select(['supplier_id', 'concat(code, \' - \', name) as name'])
            ->where(['=', 'company_id', $company_id])
            ->andWhere(['=', 'status', Constants::STATUS_ACTIVE_DB])
            ->asArray()->all();
        $suppliers = ArrayHelper::map($suppliersQuery, 'supplier_id', 'name');

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
        return $document->apply_for === Constants::DOCUMENT_APPLY_SUPPLIER_DB;
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
        return $document->has_other_transaction === Constants::OPTION_YES_DB;
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
        return $document->has_expiration === Constants::OPTION_YES_DB;
    }

    private function calculateTotalValueProduct($amountString, $unitValueString, $discountRateString)
    {
        if ($amountString && $amountString !== "" && $unitValueString && $unitValueString !== "") {
            $unitValue = (int) $unitValueString;
            $amount = (int) $amountString;
            $discountRate = 0;
            if ($discountRateString && $discountRateString !== "") {
                $discountRate = (int) $discountRateString;
            }
            $totalValue = $amount * $unitValue;
            if ($discountRate > 0) {
                $totalValue = $totalValue - ($totalValue * $discountRate / 100);
            }
            return $totalValue;
        } else {
            return "";
        }
    }

    public function actionDraft($transaction_id)
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::belongsToCompany($company_id);

        $model = $this->findModel($transaction_id);

        $transactionDto = new TransactionDto();
        $transactionDto->transaction_id = $model->transaction_id;
        $transactionDto->num_transaction = $model->num_transaction;
        $transactionDto->document_id = $model->document_id;
        $transactionDto->document = $model->document->code . ' - ' . $model->document->name;
        $transactionDto->creation_date = Utils::formatDate($model->creation_date);
        $transactionDto->expiration_date = isset($model->expiration_date) ? Utils::formatDate($model->expiration_date) : '';
        $transactionDto->linked_transaction_id = $model->linked_transaction_id;
        $transactionDto->linked_transaction = isset($model->linkedTransaction) ? $model->linkedTransaction->document->code . ' - ' . $model->linkedTransaction->num_transaction : '';
        $transactionDto->supplier_id = $model->supplier_id;
        $transactionDto->supplier = isset($model->supplier) ? $model->supplier->code . ' - ' . $model->supplier->name : '';
        $transactionDto->status = $model->status;

        $document = $model->document;
        $supplier = $model->supplier;
        $linked_transaction = $model->linkedTransaction;

        $productsQuery = Product::find()->select(['product_id', 'concat(code, \' - \', name) as name'])
            ->where(['=', 'company_id', $company_id])
            ->andWhere(['=', 'status', Constants::STATUS_ACTIVE_DB])
            ->asArray()->all();
        $products = ArrayHelper::map($productsQuery, 'product_id', 'name');

        $warehousesQuery = Warehouse::find()->select(['warehouse_id', 'concat(code, \' - \', name) as name'])
            ->where(['=', 'company_id', $company_id])
            ->andWhere(['=', 'status', Constants::STATUS_ACTIVE_DB])
            ->asArray()->all();
        $warehouses = ArrayHelper::map($warehousesQuery, 'warehouse_id', 'name');

        if (isset($this->request->post()['TransactionItemDto'])) {
            $transactionDto->transaction_items = [];
            $transactionItems = $this->request->post()['TransactionItemDto'];
            foreach ($transactionItems as $transactionItem) {
                $transactionItemDto = new TransactionItemDto();
                $transactionItemDto->product_id = $transactionItem['product_id'];
                $transactionItemDto->warehouse_id = $transactionItem['warehouse_id'];
                $transactionItemDto->amount = $transactionItem['amount'];
                $transactionItemDto->unit_value = $transactionItem['unit_value'];
                $transactionItemDto->discount_rate = $transactionItem['discount_rate'];
                $transactionItemDto->total_value = $this->calculateTotalValueProduct($transactionItemDto->amount, $transactionItemDto->unit_value, $transactionItemDto->discount_rate);

                $info = $this->actionGetProductInfo($transactionItemDto->product_id, $transactionItemDto->warehouse_id);
                if (isset($info)) {
                    $infoMap = json_decode($info);
                    if (isset($infoMap->{'taxRate'})) {
                        $transactionItemDto->tax_rate = $infoMap->{'taxRate'};
                    }
                }
                $transactionDto->transaction_items[] = $transactionItemDto;
            }
        } elseif (isset($transactionDto->linked_transaction_id)) {
            $linkedTransactionItems = TransactionItem::find()
                ->where(['=', 'company_id', $company_id])
                ->andWhere(['transaction_id' => $transaction_id])
                ->andWhere(['=', 'status', Constants::STATUS_ACTIVE_DB])
                ->asArray()->all();
            if (isset($linkedTransactionItems) && !empty($linkedTransactionItems)) {
                foreach ($linkedTransactionItems as $linkedTransactionItem) {
                    $transactionItemDto = new TransactionItemDto();
                    $transactionItemDto->product_id = $linkedTransactionItem['product_id'];
                    $transactionItemDto->warehouse_id = $linkedTransactionItem['warehouse_id'];
                    $transactionItemDto->amount = $linkedTransactionItem['amount'];
                    $transactionItemDto->unit_value = $linkedTransactionItem['unit_value'];
                    $transactionItemDto->discount_rate = $linkedTransactionItem['discount_rate'];
                    $transactionItemDto->tax_rate = $linkedTransactionItem['tax_rate'];
                    $transactionItemDto->total_value = $this->calculateTotalValueProduct($transactionItemDto->amount, $transactionItemDto->unit_value, $transactionItemDto->discount_rate);
                    $transactionDto->transaction_items[] = $transactionItemDto;
                }
            }
        } else {
            $transactionDto->transaction_items = [];
            $transactionDto->transaction_items[] = new TransactionItemDto();
        }

        if (Yii::$app->request->post('addRow') == 'true') {
            $transactionDto->transaction_items[] = new TransactionItemDto();
        }

        if (str_contains(Yii::$app->request->post('removeRow'), 'row-')) {
            $i = (int) explode('row-', Yii::$app->request->post('removeRow'))[1];
            array_splice($transactionDto->transaction_items, $i, 1);
        }

        if ($this->request->isPost && $transactionDto->load($this->request->post())) {
            $user_id = Yii::$app->user->identity->user_id;

            // echo '$transactionDto->transaction_items: ' . json_encode($transactionDto->transaction_items);
            // echo json_encode($transactionDto->errors);
            // $transaction->created_by = $user_id;
            // $transaction->updated_by = $user_id;
            // $model->updated_by = $user_id;
            // $model->updated_at = Utils::getDateNowDB();
            // if ($model->validate() && $model->save()) {
            //     return $this->redirect(['view', 'document_id' => $model->document_id]);
            // }
        }

        return $this->render('draft', [
            'model' => $model,
            'transactionDto' => $transactionDto,
            'document' => $document,
            'supplier' => $supplier,
            'linked_transaction' => $linked_transaction,
            'products' => $products,
            'warehouses' => $warehouses,
        ]);
    }

    public function actionGetProductInfo($product_id, $warehouse_id = '')
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $product = Product::findOne(['product_id' => $product_id]);
        if ($product === null) {
            return null;
        }
        if ($warehouse_id === '') {
            $warehouse_id = null;
        }
        if (isset($warehouse_id)) {
            $warehouse = Warehouse::findOne(['warehouse_id' => $warehouse_id]);
        }
        $value = $product->sugested_value;// TODO Await for kardex implementation
        return json_encode([
            'value' => $value,
            'taxRate' => $product->tax_rate,
            'discountRate' => $product->discount_rate
        ]);
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