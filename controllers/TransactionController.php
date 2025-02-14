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
                            'actions' => ['view', 'index', 'create', 'get-next-num-transaction', 'is-document-for-suppliers', 'is-document-linked-with-other-transaction', 'document-has-expiration', 'get-product-info'],
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
            'query' => Transaction::find()->where(['=', 'company_id', $company_id]),
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        // $model->creation_date = date_create('now')->format('Y-m-d H:i:s');

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

        $other_transactionsQuery = Transaction::find()->select(['transaction_id', 'concat(document.code, \' - \', num_transaction) as num_transaction'])
            ->leftJoin('document', '`document`.document_id = `transaction`.transaction_id')
            ->where(['=', '`transaction`.company_id', $company_id])
            ->andWhere(['=', '`transaction`.status', Constants::STATUS_ACTIVE_DB])
            ->orderBy(['`transaction`.created_at' => SORT_DESC, '`transaction`.num_transaction' => SORT_DESC])
            ->asArray()->all();
        $other_transactions = ArrayHelper::map($other_transactionsQuery, 'transaction_id', 'num_transaction');

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

        if (!isset($model->transaction_items)) {
            $model->transaction_items = [];
            for ($i = 0; $i < 1; $i++) {
                $model->transaction_items[] = new TransactionItemDto();
            }
        }
        // TODO include a draft view to add transaction items

        // if (Yii::$app->request->post('addRow') == 'true') {
        //     $model->transaction_items[] = new TransactionItemDto();
        // }
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $user_id = Yii::$app->user->identity->user_id;
                // $model->status = Constants::STATUS_ACTIVE_DB;
                // $model->created_by = $user_id;
                // $model->updated_by = $user_id;
                // if ($model->validate() && $model->save()) {
                //     return $this->redirect(['view', 'transaction_id' => $model->transaction_id]);
                // }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'documents' => $documents,
            'suppliers' => $suppliers,
            'other_transactions' => $other_transactions,
            'products' => $products,
            'warehouses' => $warehouses,
        ]);
    }

    public function actionGetNextNumTransaction($document_id)
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $transactions = Transaction::find()
            ->where(['=', 'document_id', $document_id])
            ->andWhere(['=', 'company_id', $company_id])
            ->andWhere(['=', 'status', Constants::STATUS_ACTIVE_DB])
            ->orderBy(['created_at' => SORT_DESC, 'num_transaction' => SORT_DESC])
            ->asArray()->all();

        $num = 0;
        $length = Constants::MAX_LENGTH_NUM_TRANSACTION;
        if ($transactions && !empty($transactions)) {
            $item = $transactions[0];
            $numTransaction = (int) $item->num_transaction;
            if ($numTransaction > $num) {
                $num = $numTransaction;
                $length = strlen($item->num_transaction);
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
}