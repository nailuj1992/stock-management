<?php

namespace app\controllers;

use app\models\Constants;
use app\models\entities\Product;
use app\models\Utils;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
                            'actions' => ['view', 'index', 'create', 'update', 'activate'],
                            'roles' => [Constants::ROLE_USER],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'activate' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()->where(['=', 'company_id', $company_id]),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'product_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'companyId' => $company_id,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param int $product_id Product ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($product_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($product_id, true),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateOwnerOrSupervisorOfCompany($company_id);
        $model = new Product();
        $model->company_id = $company_id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $user_id = Yii::$app->user->identity->user_id;
                $model->status = Constants::STATUS_ACTIVE_DB;
                $model->created_by = $user_id;
                $model->updated_by = $user_id;
                if ($model->validate() && $model->save()) {
                    return $this->redirect(['view', 'product_id' => $model->product_id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $product_id Product ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($product_id)
    {
        $model = $this->findModel($product_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $user_id = Yii::$app->user->identity->user_id;
            $model->updated_by = $user_id;
            $model->updated_at = Utils::getDateNowDB();
            if ($model->validate() && $model->save()) {
                return $this->redirect(['view', 'product_id' => $model->product_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $product_id Product ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActivate($product_id)
    {
        $model = $this->findModel($product_id);

        if ($model->isActive()) {
            $model->status = Constants::STATUS_INACTIVE_DB;
        } elseif ($model->isInactive()) {
            $model->status = Constants::STATUS_ACTIVE_DB;
        }

        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $product_id Product ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($product_id, $view = false)
    {
        $model = Product::findOne(['product_id' => $product_id]);

        if ($model !== null) {
            if (!$view) {
                Utils::validateOwnerOrSupervisorOfCompany($model->company_id);
            } else {
                Utils::validateBelongsToCompany($model->company_id);
            }
            Utils::validateCompanyMatches($model->company_id);
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', Constants::MESSAGE_PAGE_NOT_EXISTS));
    }
}
