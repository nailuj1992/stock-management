<?php

namespace app\controllers;

use app\models\Constants;
use app\models\entities\City;
use app\models\entities\Country;
use app\models\entities\State;
use app\models\entities\Warehouse;
use app\models\TextConstants;
use app\models\Utils;
use app\models\WarehouseEdit;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WarehouseController implements the CRUD actions for Warehouse model.
 */
class WarehouseController extends Controller
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
     * Lists all Warehouse models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateBelongsToCompany($company_id);

        $dataProvider = new ActiveDataProvider([
            'query' => Warehouse::find()->where(['=', 'company_id', $company_id]),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'warehouse_id' => SORT_DESC,
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
     * Displays a single Warehouse model.
     * @param int $warehouse_id Warehouse ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($warehouse_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($warehouse_id, true),
        ]);
    }

    /**
     * Creates a new Warehouse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        Utils::validateCompanySelected();
        $company_id = Utils::getCompanySelected();

        Utils::validateOwnerOrSupervisorOfCompany($company_id);
        $model = new WarehouseEdit();
        $model->company_id = $company_id;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $user_id = Yii::$app->user->identity->user_id;
            $warehouse = new Warehouse();
            $warehouse->code = $model->code;
            $warehouse->name = $model->name;
            $warehouse->company_id = $model->company_id;
            $warehouse->address = $model->address;
            $warehouse->city = $model->city;
            $warehouse->status = Constants::STATUS_ACTIVE_DB;
            $warehouse->created_by = $user_id;
            $warehouse->updated_by = $user_id;

            if ($warehouse->save()) {
                return $this->redirect(['view', 'warehouse_id' => $warehouse->warehouse_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Warehouse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $warehouse_id Warehouse ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($warehouse_id)
    {
        $warehouse = $this->findModel($warehouse_id);
        $model = new WarehouseEdit();
        $model->warehouse_id = $warehouse->warehouse_id;
        $model->code = $warehouse->code;
        $model->name = $warehouse->name;
        $model->company_id = $warehouse->company_id;
        $model->address = $warehouse->address;

        $city = City::findOne(['city_id' => $warehouse->city]);
        $state = State::findOne(['state_id' => $city->state_id]);
        $country = Country::findOne(['country_id' => $state->country_id]);
        $model->city = $city->city_id;
        $model->state = $state->state_id;
        $model->country = $country->country_id;

        $model->setIsNewRecord(false);
        if ($this->request->isPost && $model->load($this->request->post())) {
            $user_id = Yii::$app->user->identity->user_id;
            $warehouse->code = $model->code;
            $warehouse->name = $model->name;
            $warehouse->company_id = $model->company_id;
            $warehouse->address = $model->address;
            $warehouse->city = $model->city;
            $warehouse->updated_by = $user_id;
            $warehouse->updated_at = Utils::getDateNowDB();

            if ($warehouse->validate() && $warehouse->save()) {
                return $this->redirect(['view', 'warehouse_id' => $warehouse->warehouse_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'warehouse' => $warehouse,
        ]);
    }

    /**
     * Deletes an existing Warehouse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $warehouse_id Warehouse ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActivate($warehouse_id)
    {
        $model = $this->findModel($warehouse_id);

        if ($model->isActive()) {
            $model->status = Constants::STATUS_INACTIVE_DB;
        } elseif ($model->isInactive()) {
            $model->status = Constants::STATUS_ACTIVE_DB;
        }

        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Warehouse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $warehouse_id Warehouse ID
     * @return Warehouse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($warehouse_id, $view = false)
    {
        $model = Warehouse::findOne(['warehouse_id' => $warehouse_id]);

        if ($model !== null) {
            if (!$view) {
                Utils::validateOwnerOrSupervisorOfCompany($model->company_id);
            } else {
                Utils::validateBelongsToCompany($model->company_id);
            }
            Utils::validateCompanyMatches($model->company_id);
            return $model;
        }

        throw new NotFoundHttpException(Yii::t(TextConstants::APP, TextConstants::MESSAGE_PAGE_NOT_EXISTS));
    }
}
