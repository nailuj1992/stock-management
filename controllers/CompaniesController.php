<?php

namespace app\controllers;

use yii;
use app\models\Constants;
use app\models\UserCompanyForm;
use app\models\CompanyForm;
use app\models\Utils;
use app\models\entities\City;
use app\models\entities\State;
use app\models\entities\Country;
use app\models\entities\User;
use app\models\entities\UserCompany;
use app\models\entities\Company;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * CompaniesController implements the CRUD actions for Company and UserCompany models.
 */
class CompaniesController extends Controller
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
                            'actions' => ['view', 'index', 'create', 'update', 'list-users', 'view-user', 'create-user', 'change-role', 'activate', 'select'],
                            'roles' => [Constants::ROLE_USER],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'change-role' => ['POST'],
                        'activate' => ['POST'],
                        'select' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Utils::validateActiveUser();
        $dataProvider = new ActiveDataProvider([
            'query' => UserCompany::queryGetCompaniesForUser(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'company_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        $dataProviderListUserNotBelong = new ActiveDataProvider([
            'query' => Company::queryGetCompaniesNotBelongUser(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'company_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderListUserNotBelong' => $dataProviderListUserNotBelong,
        ]);
    }

    /**
     * Displays a single Company model.
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($company_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($company_id),
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        Utils::validateActiveUser();
        $model = new CompanyForm();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $user_id = Yii::$app->user->identity->user_id;
                $company = new Company();
                $company->code = $model->code;
                $company->name = $model->name;
                $company->phone = $model->phone;
                $company->address = $model->address;
                $company->city = $model->city;
                $company->status = Constants::STATUS_ACTIVE_DB;
                $company->created_by = $user_id;
                $company->updated_by = $user_id;

                if ($model->validate() && $company->save()) {
                    $userCompany = new UserCompany();
                    $userCompany->user_id = $user_id;
                    $userCompany->company_id = $company->company_id;
                    $userCompany->role = Constants::ROLE_OWNER_DB;
                    $userCompany->status = Constants::STATUS_ACTIVE_DB;
                    $userCompany->created_by = $user_id;
                    $userCompany->updated_by = $user_id;

                    if ($userCompany->save()) {
                        $transaction->commit();
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();
                    }
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($company_id)
    {
        Utils::validateOwnerOfCompany($company_id);

        $userCompany = $this->findModel($company_id);
        $model = new CompanyForm();
        $model->company_id = $userCompany->company->company_id;
        $model->code = $userCompany->company->code;
        $model->name = $userCompany->company->name;
        $model->phone = $userCompany->company->phone;
        $model->address = $userCompany->company->address;

        $city = City::findOne(['city_id' => $userCompany->company->city]);
        $state = State::findOne(['state_id' => $city->state_id]);
        $country = Country::findOne(['country_id' => $state->country_id]);
        $model->city = $city->city_id;
        $model->state = $state->state_id;
        $model->country = $country->country_id;

        $model->setIsNewRecord(false);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $user_id = Yii::$app->user->identity->user_id;
            $userCompany->company->code = $model->code;
            $userCompany->company->name = $model->name;
            $userCompany->company->phone = $model->phone;
            $userCompany->company->address = $model->address;
            $userCompany->company->city = $model->city;
            $userCompany->company->updated_by = $user_id;
            $userCompany->company->updated_at = Utils::getDateNowDB();

            if ($model->validate() && $userCompany->company->save()) {
                return $this->redirect(['view', 'company_id' => $userCompany->company->company_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'userCompany' => $userCompany,
        ]);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @return UserCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($company_id)
    {
        Utils::validateActiveUser();
        if (($model = UserCompany::findCompanyForUser($company_id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', Constants::MESSAGE_PAGE_NOT_EXISTS));
    }

    public function actionListUsers($company_id)
    {
        Utils::validateOwnerOrSupervisorOfCompany($company_id);
        $company = Company::findOne($company_id);

        $dataProvider = new ActiveDataProvider([
            'query' => UserCompany::queryGetAllUsersForCompany($company_id),
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'company_id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('list-users', [
            'dataProvider' => $dataProvider,
            'company' => $company,
        ]);
    }

    public function actionViewUser($user_id, $company_id)
    {
        Utils::validateOwnerOrSupervisorOfCompany($company_id);
        $company = Company::findOne($company_id);

        return $this->render('view-user', [
            'model' => UserCompany::getDetailedUserInfoForCompany($user_id, $company_id),
            'company' => $company,
        ]);
    }

    public function actionCreateUser($company_id)
    {
        Utils::validateOwnerOrSupervisorOfCompany($company_id);
        $company = Company::findOne($company_id);

        $model = new UserCompanyForm();
        $randomPass = "12345678";//Utils::randPass();// TODO Change this line when a mail to send the actual password is supported.

        if ($this->request->isPost && $model->load($this->request->post())) {
            $user_id = Yii::$app->user->identity->user_id;

            $transaction = Yii::$app->db->beginTransaction();

            try {
                $user = new User();
                $user->email = $model->email;
                $user->password = Utils::sha($randomPass);
                $user->name = $model->name;
                $user->phone = $model->phone;
                $user->address = $model->address;
                $user->city = $model->city;
                $user->status = Constants::STATUS_INACTIVE_DB;
                $user->created_by = $user_id;
                $user->updated_by = $user_id;

                if ($model->validate() && $user->save()) {
                    $auth = Yii::$app->authManager;
                    $authorRole = $auth->getRole(Constants::ROLE_USER);
                    $auth->assign($authorRole, $user->user_id);

                    $userCompany = new UserCompany();
                    $userCompany->user_id = $user->user_id;
                    $userCompany->company_id = $company_id;
                    $userCompany->role = $model->role;
                    $userCompany->status = Constants::STATUS_ACTIVE_DB;
                    $userCompany->created_by = $user_id;
                    $userCompany->updated_by = $user_id;

                    if ($userCompany->save()) {
                        $transaction->commit();
                        return $this->redirect(['list-users', 'company_id' => $company_id]);
                    } else {
                        $transaction->rollBack();
                    }
                } else {
                    $transaction->rollBack();
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('create-user', [
            'model' => $model,
            'company' => $company,
        ]);
    }

    /**
     * Promotes/demotes an existing User inside a Company.
     * If promotion/demotion is successful, the browser will be redirected to the 'index' page.
     * @param int $user_id User ID
     * @param int $company_id Company ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionChangeRole($user_id, $company_id)
    {
        Utils::validateOwnerOfCompany($company_id);

        $model = UserCompany::findUserCompanyRow($user_id, $company_id);
        $model->setIsNewRecord(false);

        if (!isset($model)) {
            throw new NotFoundHttpException(Yii::t('app', Constants::MESSAGE_PAGE_NOT_EXISTS));
        }
        if ($model->isOwner()) {
            throw new ForbiddenHttpException(Yii::t('app', Constants::MESSAGE_OWNER_NOT_DEMOTE));
        }
        if ($model->isInactive()) {
            throw new ForbiddenHttpException(Yii::t('app', Constants::MESSAGE_CANNOT_PROMOTE_INACTIVE));
        }

        if ($model->isMember()) {
            $model->role = Constants::ROLE_SUPERVISOR_DB;
        } elseif ($model->isSupervisor()) {
            $model->role = Constants::ROLE_MEMBER_DB;
        }
        $model->save();

        return $this->redirect(['list-users', 'company_id' => $company_id]);
    }

    /**
     * Activated/deactivates an existing User inside a Company.
     * If activation/deactivation is successful, the browser will be redirected to the 'index' page.
     * @param mixed $user_id User ID
     * @param mixed $company_id Company ID
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     * @return yii\web\Response
     */
    public function actionActivate($user_id, $company_id)
    {
        Utils::validateOwnerOrSupervisorOfCompany($company_id);

        $user = UserCompany::findUserCompanyRow(Yii::$app->user->identity->user_id, $company_id);
        $model = UserCompany::findUserCompanyRow($user_id, $company_id);
        $model->setIsNewRecord(false);

        if (!isset($model)) {
            throw new NotFoundHttpException(Yii::t('app', Constants::MESSAGE_PAGE_NOT_EXISTS));
        }
        if ($user->user_id == $user_id) {
            throw new ForbiddenHttpException(Yii::t('app', Constants::MESSAGE_CANNOT_ACTIVATE_YOURSELF));
        }
        if ($model->isOwner()) {
            throw new ForbiddenHttpException(Yii::t('app', Constants::MESSAGE_OWNER_NOT_ACTIVATE));
        }
        if ($user->isSupervisor() && $model->isSupervisor()) {
            throw new ForbiddenHttpException(Yii::t('app', Constants::MESSAGE_CANNOT_ACTIVATE_SUPERVISORS));
        }

        if ($model->isActive()) {
            $model->status = Constants::STATUS_INACTIVE_DB;
        } elseif ($model->isInactive()) {
            $model->status = Constants::STATUS_ACTIVE_DB;
        }
        $model->save();

        return $this->redirect(['list-users', 'company_id' => $company_id]);
    }

    public function actionSelect($company_id)
    {
        Utils::validateActiveUser();
        Utils::validateBelongsToCompany($company_id);

        $session = Yii::$app->session;
        $session->set(Constants::SELECTED_COMPANY_ID, $company_id);

        return $this->redirect(['/']);
    }

}
