<?php

namespace app\controllers;

use app\models\Constants;
use app\models\entities\ApplicationCompany;
use app\models\entities\Company;
use app\models\entities\UserCompany;
use app\models\TextConstants;
use app\models\Utils;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ApplicationController implements the CRUD actions for ApplicationCompany model.
 */
class ApplicationController extends Controller
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
                            'actions' => ['view', 'index', 'create', 'list', 'list-pending', 'approve', 'deny'],
                            'roles' => [Constants::ROLE_USER],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'approve' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all ApplicationCompany models.
     *
     * @return string
     */
    public function actionIndex($company_id)
    {
        Utils::validateActiveUser();

        $dataProvider = new ActiveDataProvider([
            'query' => ApplicationCompany::queryGetAllApplicationsForUser($company_id),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'application_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'company_id' => $company_id,
        ]);
    }

    public function actionList()
    {
        Utils::validateActiveUser();

        $dataProvider = new ActiveDataProvider([
            'query' => ApplicationCompany::queryGetAllApplicationsForUser(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'application_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionListPending($company_id)
    {
        Utils::validateOwnerOrSupervisorOfCompany($company_id);
        $company = Company::findOne($company_id);

        $dataProvider = new ActiveDataProvider([
            'query' => ApplicationCompany::queryGetPendingApplicationsForCompany($company_id),
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'company_id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('list-pending', [
            'dataProvider' => $dataProvider,
            'company' => $company,
        ]);
    }

    /**
     * Displays a single ApplicationCompany model.
     * @param int $application_id Application ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($application_id, $show_list = false, $is_company = false)
    {
        return $this->render('view', [
            'model' => $this->findModel($application_id),
            'showList' => $show_list,
            'isCompany' => $is_company,
        ]);
    }

    /**
     * Creates a new ApplicationCompany model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($company_id)
    {
        Utils::validateActiveUser();
        $user_id = Yii::$app->user->identity->user_id;

        if (count(ApplicationCompany::getPendingApplicationsForCompany($company_id, $user_id)) > 0) {
            throw new ForbiddenHttpException(Yii::t(TextConstants::APPLICATION, TextConstants::APPLICATION_MESSAGE_NOT_CREATE_NEW_APPLICATION_EXISTING_PENDING));
        }
        $model = new ApplicationCompany();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (!isset($model->comment_user) || strlen(trim($model->comment_user)) == 0) {
                    $model->addError('comment_user', Yii::t(TextConstants::APPLICATION, TextConstants::COMPANY_APPLICATION_MESSAGE_COMMENT));
                }

                $model->user_id = $user_id;
                $model->company_id = $company_id;
                $model->status = Constants::STATUS_PENDING_DB;
                $model->comment_user = trim($model->comment_user);
                $model->created_by = $user_id;
                $model->updated_by = $user_id;

                if (count($model->errors) == 0 && $model->validate() && $model->save()) {
                    return $this->redirect(['view', 'application_id' => $model->application_id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'company_id' => $company_id,
        ]);
    }

    public function actionApprove($application_id)
    {
        $model = $this->findModel($application_id);
        $user_id = Yii::$app->user->identity->user_id;
        Utils::validateOwnerOrSupervisorOfCompany($model->company_id);

        $model->status = Constants::STATUS_APPROVED_DB;
        $model->updated_by = $user_id;

        $transaction = Yii::$app->db->beginTransaction();
        $success = true;

        $userCompany = UserCompany::findUserCompanyRow($model->user_id, $model->company_id);
        if (isset($userCompany) && $userCompany->status == Constants::STATUS_INACTIVE_DB) {
            $userCompany->setIsNewRecord(false);
            $userCompany->status = Constants::STATUS_ACTIVE_DB;
            $userCompany->updated_by = $user_id;
        } else {
            $userCompany = new UserCompany();
            $userCompany->user_id = $model->user_id;
            $userCompany->company_id = $model->company_id;
            $userCompany->role = Constants::ROLE_MEMBER_DB;
            $userCompany->status = Constants::STATUS_ACTIVE_DB;
            $userCompany->selected_company = Constants::FALSE;
            $userCompany->created_by = $user_id;
            $userCompany->updated_by = $user_id;
        }

        $success &= $userCompany->save();
        $success &= $model->save();

        if ($success) {
            $transaction->commit();
        } else {
            $transaction->rollBack();
        }

        if (count(ApplicationCompany::getPendingApplicationsForCompany($model->company_id)) > 0) {
            return $this->redirect(['list-pending', 'company_id' => $model->company_id]);
        } else {
            return $this->redirect(['companies/list-users', 'company_id' => $model->company_id]);
        }
    }

    public function actionDeny($application_id)
    {
        $model = $this->findModel($application_id);
        $user_id = Yii::$app->user->identity->user_id;
        Utils::validateOwnerOrSupervisorOfCompany($model->company_id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (!isset($model->comment_company) || strlen(trim($model->comment_company)) == 0) {
                    $model->addError('comment_company', Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_USERS_MESSAGE_FEEDBACK));
                }

                $model->status = Constants::STATUS_REJECTED_DB;
                $model->comment_company = trim($model->comment_company);
                $model->updated_by = $user_id;

                if (count($model->errors) == 0 && $model->validate() && $model->save()) {
                    if (count(ApplicationCompany::getPendingApplicationsForCompany($model->company_id)) > 0) {
                        return $this->redirect(['list-pending', 'company_id' => $model->company_id]);
                    } else {
                        return $this->redirect(['companies/list-users', 'company_id' => $model->company_id]);
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('deny', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the ApplicationCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $application_id Application ID
     * @return ApplicationCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($application_id)
    {
        Utils::validateActiveUser();
        if (($model = ApplicationCompany::findOne(['application_id' => $application_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t(TextConstants::APP, TextConstants::MESSAGE_PAGE_NOT_EXISTS));
    }
}
