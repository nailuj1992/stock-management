<?php

namespace app\controllers;

use app\models\Constants;
use app\models\entities\State;
use app\models\TextConstants;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\bootstrap5\Html;

/**
 * StateController implements the CRUD actions for State model.
 */
class StateController extends Controller
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
                            'actions' => ['dynamic-states'],
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
     * Lists all State models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => State::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'state_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single State model.
     * @param int $state_id State ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($state_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($state_id),
        ]);
    }

    /**
     * Creates a new State model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new State();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'state_id' => $model->state_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing State model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $state_id State ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($state_id)
    {
        $model = $this->findModel($state_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'state_id' => $model->state_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing State model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $state_id State ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($state_id)
    {
        $this->findModel($state_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the State model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $state_id State ID
     * @return State the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($state_id)
    {
        if (($model = State::findOne(['state_id' => $state_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', Constants::MESSAGE_PAGE_NOT_EXISTS));
    }

    public function actionDynamicStates($country_id, $state_id = '')
    {
        if (Yii::$app->request->isGet) {
            $states = State::getStates($country_id);
            $resp = Html::tag('option', Html::encode(Yii::t(TextConstants::APP, TextConstants::OPTION_SELECT)), ['value' => '']);
            foreach ($states as $key => $value) {
                $resp .= Html::tag('option', Html::encode($value), ['value' => $key, 'selected' => $state_id !== '' && $state_id == $key]);
            }
            return $resp;
        }
    }
}
