<?php

namespace app\controllers;

use yii;
use app\models\Constants;
use app\models\UserEdit;
use app\models\UserPassword;
use app\models\UserSignUp;
use app\models\UserLogin;
use app\models\Utils;
use app\models\entities\City;
use app\models\entities\State;
use app\models\entities\Country;
use app\models\entities\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                            'actions' => ['view'],
                            'roles' => [Constants::ROLE_ADMIN, Constants::ROLE_USER],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['create'],
                            'roles' => ['?'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['update', 'change'],
                            'roles' => [Constants::ROLE_USER],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Displays a single User model.
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        return $this->render('view', [
            'model' => $this->findModel(),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new UserSignUp();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $user = new User();
            $user->email = $model->email;
            $user->password = Utils::sha($model->password);
            $user->name = $model->name;
            $user->phone = $model->phone;
            $user->address = $model->address;
            $user->city = $model->city;
            $user->status = Constants::STATUS_ACTIVE_DB;
            $user->created_by = Constants::DEFAULT_USER_CREATE;
            $user->updated_by = Constants::DEFAULT_USER_CREATE;

            if ($model->validate() && $user->save()) {
                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole(Constants::ROLE_USER);
                $auth->assign($authorRole, $user->user_id);

                $userLogin = UserLogin::findByEmail($user->email);
                if (Yii::$app->user->login($userLogin, 0)) {
                    return $this->redirect(['/']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $user = $this->findModel();
        $model = new UserEdit();
        $model->user_id = $user->user_id;
        $model->email = $user->email;
        $model->name = $user->name;
        $model->phone = $user->phone;
        $model->address = $user->address;

        $city = City::findOne(['city_id' => $user->city]);
        $state = State::findOne(['state_id' => $city->state_id]);
        $country = Country::findOne(['country_id' => $state->country_id]);
        $model->city = $city->city_id;
        $model->state = $state->state_id;
        $model->country = $country->country_id;

        $model->setIsNewRecord(false);
        if ($this->request->isPost && $model->load($this->request->post())) {
            $user->email = $model->email;
            $user->name = $model->name;
            $user->phone = $model->phone;
            $user->address = $model->address;
            $user->city = $model->city;
            $user->updated_by = $model->user_id;
            $user->updated_at = Utils::getDateNowDB();

            if ($model->validate() && $user->save()) {
                return $this->redirect(['view', 'user_id' => $model->user_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Changes the password for the existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionChange()
    {
        $user = $this->findModel();
        $model = new UserPassword();
        $model->user_id = $user->user_id;
        $model->email = $user->email;
        $model->setIsNewRecord(false);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $user->email = $model->email;
            $user->password = Utils::sha($model->password);
            if ($user->isInactive()) {
                $user->status = Constants::STATUS_ACTIVE_DB;
            }
            $user->updated_by = $model->user_id;
            $user->updated_at = Utils::getDateNowDB();

            if ($model->validate() && $user->save()) {
                return $this->redirect(['view', 'user_id' => $model->user_id]);
            }
        }

        return $this->render('change', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel()
    {
        $user_id = Yii::$app->user->identity->user_id;

        if (($model = User::findOne(['user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', Constants::MESSAGE_PAGE_NOT_EXISTS));
    }
}
