<?php

namespace app\controllers;

use app\models\Constants;
use app\models\entities\Document;
use app\models\TextConstants;
use app\models\Utils;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
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
                    'class' => VerbFilter::class,
                    'actions' => [
                        'activate' => ['POST'],
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
            'query' => Document::find()->where(['=', 'company_id', $company_id]),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'document_id' => SORT_DESC,
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
     * Displays a single Document model.
     * @param int $document_id Document ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($document_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($document_id, true),
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

        Utils::validateOwnerOrSupervisorOfCompany($company_id);
        $model = new Document();
        $model->company_id = $company_id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $user_id = Yii::$app->user->identity->user_id;
                $model->status = Constants::STATUS_ACTIVE_DB;
                $model->created_by = $user_id;
                $model->updated_by = $user_id;
                if ($model->validate() && $model->save()) {
                    return $this->redirect(['view', 'document_id' => $model->document_id]);
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
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $document_id Document ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($document_id)
    {
        $model = $this->findModel($document_id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $user_id = Yii::$app->user->identity->user_id;
            $model->updated_by = $user_id;
            $model->updated_at = Utils::getDateNowDB();
            if ($model->validate() && $model->save()) {
                return $this->redirect(['view', 'document_id' => $model->document_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $document_id Document ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionActivate($document_id)
    {
        $model = $this->findModel($document_id);

        if ($model->isActive()) {
            $model->status = Constants::STATUS_INACTIVE_DB;
        } elseif ($model->isInactive()) {
            $model->status = Constants::STATUS_ACTIVE_DB;
        }

        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $document_id Document ID
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($document_id, $view = false)
    {
        $model = Document::findOne(['document_id' => $document_id]);

        if ($model !== null) {
            Utils::validateCompanySelected();
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
