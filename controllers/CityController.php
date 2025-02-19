<?php

namespace app\controllers;

use app\models\entities\City;
use app\models\entities\State;
use app\models\CityForm;
use app\models\CitySearch;
use app\models\Constants;
use app\models\TextConstants;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\bootstrap5\Html;

/**
 * CityController implements the CRUD actions for City model.
 */
class CityController extends Controller
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
                            'actions' => ['view', 'index', 'create', 'update', 'delete'],
                            'roles' => [Constants::ROLE_ADMIN],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['dynamic-cities'],
                            'roles' => [Constants::ROLE_ADMIN, Constants::ROLE_USER, '?'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all City models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CitySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single City model.
     * @param int $city_id City ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($city_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($city_id),
        ]);
    }

    /**
     * Creates a new City model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $city = new City();
        $model = new CityForm();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $city->code = $model->code;
            $city->name = $model->name;
            $city->state_id = $model->state;

            if ($model->validate() && $city->save()) {
                return $this->redirect(['view', 'city_id' => $city->city_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'city' => $city,
        ]);
    }

    /**
     * Updates an existing City model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $city_id City ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($city_id)
    {
        $city = $this->findModel($city_id);
        $model = new CityForm();
        $model->city_id = $city->city_id;
        $model->code = $city->code;
        $model->name = $city->name;
        $model->state = $city->state_id;
        $model->setIsNewRecord(false);

        $state = State::findOne(['state_id' => $model->state]);
        $model->country = $state->country_id;

        if ($this->request->isPost && $model->load($this->request->post())) {
            $city->code = $model->code;
            $city->name = $model->name;
            $city->state_id = $model->state;

            if ($model->validate() && $city->save()) {
                return $this->redirect(['view', 'city_id' => $city->city_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing City model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $city_id City ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($city_id)
    {
        $this->findModel($city_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the City model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $city_id City ID
     * @return City the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($city_id)
    {
        if (($model = City::findOne(['city_id' => $city_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t(TextConstants::APP, TextConstants::MESSAGE_PAGE_NOT_EXISTS));
    }

    public function actionDynamicCities($state_id, $city_id = '')
    {
        if (Yii::$app->request->isGet) {
            $cities = City::getCities($state_id);
            $resp = Html::tag('option', Html::encode(Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)), ['value' => '']);
            foreach ($cities as $key => $value) {
                $resp .= Html::tag('option', Html::encode($value), ['value' => $key, 'selected' => $city_id !== '' && $city_id == $key]);
            }
            return $resp;
        }
    }
}
