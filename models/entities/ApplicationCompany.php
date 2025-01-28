<?php

namespace app\models\entities;

use app\models\Constants;
use app\models\Utils;
use Yii;

/**
 * This is the model class for table "application_company".
 *
 * @property int $application_id
 * @property int|null $user_id
 * @property int|null $company_id
 * @property string $status
 * @property string|null $comment_user
 * @property string|null $comment_company
 * @property int|null $created_by
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $updated_at
 *
 * @property Company $company
 * @property User $user
 */
class ApplicationCompany extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'company_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 1],
            [['comment_user', 'comment_company'], 'string', 'max' => 500],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'company_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'application_id' => Yii::t('app', 'Application ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'company_id' => Yii::t('app', 'Company ID'),
            'status' => Yii::t('app', 'Status'),
            'comment_user' => Yii::t('app', 'Comment'),
            'comment_company' => Yii::t('app', 'Feedback'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::class, ['company_id' => 'company_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    public function getFullStatus()
    {
        return Utils::getFullStatusApplication($this->status);
    }

    public function isApproved()
    {
        return Utils::isApproved($this->status);
    }

    public function isRejected()
    {
        return Utils::isRejected($this->status);
    }

    public function isPending()
    {
        return Utils::isPending($this->status);
    }

    public static function queryGetAllApplicationsForUser($company_id = null)
    {
        $user_id = Yii::$app->user->identity->user_id;
        $query = self::find()->joinWith('company')->joinWith('user')
            ->where(['like', 'user.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['=', 'user.user_id', $user_id]);
        if (isset($company_id)) {
            $query->andWhere(['=', 'company.company_id', $company_id]);
        }
        $query = $query->orderBy(['updated_at' => SORT_DESC]);
        return $query;
    }

    public static function getAllApplicationsForUser($company_id = null)
    {
        return self::queryGetAllApplicationsForUser($company_id)->asArray()->all();
    }

    public static function queryGetPendingApplicationsForUser($company_id = null)
    {
        $user_id = Yii::$app->user->identity->user_id;
        $query = self::find()->joinWith('company')->joinWith('user')
            ->where(['like', 'user.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['=', 'user.user_id', $user_id])
            ->andWhere(['=', 'application_company.status', Constants::STATUS_PENDING_DB]);
        if (isset($company_id)) {
            $query->andWhere(['=', 'company.company_id', $company_id]);
        }
        $query = $query->orderBy(['created_at' => SORT_DESC]);
        return $query;
    }

    public static function getPendingApplicationsForUser($company_id = null)
    {
        return self::queryGetPendingApplicationsForUser($company_id)->asArray()->all();
    }

    public static function queryGetPendingApplicationsForCompany($company_id, $user_id = null)
    {
        $query = self::find()->joinWith('company')->joinWith('user')
            ->where(['=', 'application_company.status', Constants::STATUS_PENDING_DB])
            ->andWhere(['=', 'company.company_id', $company_id]);
        if (isset($user_id)) {
            $query = $query->andWhere(['=', 'user.user_id', $user_id]);
        }
        $query = $query->orderBy(['created_at' => SORT_DESC]);
        return $query;
    }

    public static function getPendingApplicationsForCompany($company_id, $user_id = null)
    {
        return self::queryGetPendingApplicationsForCompany($company_id, $user_id)->asArray()->all();
    }
}
