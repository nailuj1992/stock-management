<?php

namespace app\controllers;

use app\models\Constants;
use app\models\entities\City;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\entities\Supplier;
use app\models\SupplierEdit;
use app\models\Utils;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends Controller
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
     * Lists all Supplier models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $dataProvider = new ActiveDataProvider([
            'query' => Supplier::find()->where(['=', 'company_id', $company_id]),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'supplier_id' => SORT_DESC,
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
     * Displays a single Supplier model.
     * @param int $supplier_id Supplier ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($supplier_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($supplier_id, true),
        ]);
    }

    /**
     * Creates a new Supplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateOwnerOrSupervisorOfCompany($company_id);
        $model = new SupplierEdit();
        $model->company_id = $company_id;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $user_id = Yii::$app->user->identity->user_id;
            $supplier = new Supplier();
            $supplier->code = $model->code;
            $supplier->name = $model->name;
            $supplier->company_id = $model->company_id;
            $supplier->email = $model->email;
            $supplier->phone = $model->phone;
            $supplier->address = $model->address;
            $supplier->city = $model->city;
            $supplier->status = Constants::STATUS_ACTIVE_DB;
            $supplier->created_by = $user_id;
            $supplier->updated_by = $user_id;

            if ($supplier->save()) {
                return $this->redirect(['view', 'supplier_id' => $supplier->supplier_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Supplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $supplier_id Supplier ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($supplier_id)
    {
        $supplier = $this->findModel($supplier_id);
        $model = new SupplierEdit();
        $model->supplier_id = $supplier->supplier_id;
        $model->code = $supplier->code;
        $model->name = $supplier->name;
        $model->company_id = $supplier->company_id;
        $model->email = $supplier->email;
        $model->phone = $supplier->phone;
        $model->address = $supplier->address;

        $city = City::findOne(['city_id' => $supplier->city]);
        $state = State::findOne(['state_id' => $city->state_id]);
        $country = Country::findOne(['country_id' => $state->country_id]);
        $model->city = $city->city_id;
        $model->state = $state->state_id;
        $model->country = $country->country_id;

        $model->setIsNewRecord(false);
        if ($this->request->isPost && $model->load($this->request->post())) {
            $user_id = Yii::$app->user->identity->user_id;
            $supplier->code = $model->code;
            $supplier->name = $model->name;
            $supplier->company_id = $model->company_id;
            $supplier->email = $model->email;
            $supplier->phone = $model->phone;
            $supplier->address = $model->address;
            $supplier->city = $model->city;
            $supplier->updated_by = $user_id;
            $supplier->updated_at = Utils::getDateNowDB();

            if ($supplier->validate() && $supplier->save()) {
                return $this->redirect(['view', 'supplier_id' => $supplier->supplier_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'supplier' => $supplier,
        ]);
    }

    /**
     * Deletes an existing Supplier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $supplier_id Supplier ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActivate($supplier_id)
    {
        $model = $this->findModel($supplier_id);

        if ($model->isActive()) {
            $model->status = Constants::STATUS_INACTIVE_DB;
        } elseif ($model->isInactive()) {
            $model->status = Constants::STATUS_ACTIVE_DB;
        }

        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Supplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $supplier_id Supplier ID
     * @return Supplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($supplier_id, $view = false)
    {
        $model = Supplier::findOne(['supplier_id' => $supplier_id]);

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
