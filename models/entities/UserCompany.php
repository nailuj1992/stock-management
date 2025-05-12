<?php

namespace app\models\entities;

use app\models\Constants;
use app\models\TextConstants;
use app\models\Utils;
use Yii;
use function PHPUnit\Framework\isEmpty;

/**
 * This is the model class for table "user_company".
 *
 * @property int $user_id
 * @property int $company_id
 * @property string $role
 * @property int $selected_company
 * @property string $status
 * @property int|null $created_by
 * @property string|null $created_at
 * @property int|null $updated_by
 * @property string|null $updated_at
 *
 * @property Company $company
 * @property User $user
 */
class UserCompany extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'company_id', 'selected_company', 'role'], 'required'],
            [['user_id', 'company_id', 'selected_company', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['role', 'status'], 'string', 'max' => 1],
            [['user_id', 'company_id'], 'unique', 'targetAttribute' => ['user_id', 'company_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'company_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t(TextConstants::USER, TextConstants::USER_MODEL_ID),
            'company_id' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_ID),
            'role' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_ROLE),
            'status' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_STATUS),
            'selected_company' => Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MODEL_SELECTED),
            'created_by' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CREATED_BY),
            'created_at' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_CREATED_AT),
            'updated_by' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_UPDATED_BY),
            'updated_at' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_UPDATED_AT),
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

    public function isActive()
    {
        return Utils::isActive($this->status);
    }

    public function isInactive()
    {
        return Utils::isInactive($this->status);
    }

    public function isMember()
    {
        return Utils::isMember($this->role);
    }

    public function isSupervisor()
    {
        return Utils::isSupervisor($this->role);
    }

    public function isOwner()
    {
        return Utils::isOwner($this->role);
    }

    public function getFullRole()
    {
        return Utils::getFullRole($this->role);
    }

    public function getFullStatus()
    {
        return Utils::getFullStatus($this->status);
    }

    public function isSelected()
    {
        return Utils::isTrue($this->selected_company);
    }

    private static function queryCompanySelected(): array
    {
        $user_id = Yii::$app->user->identity->user_id;
        $query = self::find()->joinWith('company')->joinWith('user')
            ->where(['like', 'user.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['like', 'user_company.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['=', 'user.user_id', $user_id])
            ->andWhere(['=', 'user_company.selected_company', Constants::TRUE]);
        return $query->all();
    }

    public static function getCompanySelected()
    {
        $results = self::queryCompanySelected();
        return !empty($results) ? $results[0]->company_id : null;
    }

    public static function hasCompanySelected(): bool
    {
        $results = self::queryCompanySelected();
        return !empty($results);
    }

    public static function isUserInRolesOfCompany($company_id, array $roles)
    {
        $user_id = Yii::$app->user->identity->user_id;
        $query = self::find()->joinWith('company')->joinWith('user')
            ->where(['like', 'user.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['like', 'company.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['like', 'user_company.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['=', 'user.user_id', $user_id])
            ->andWhere(['=', 'company.company_id', $company_id])
            ->andWhere(['IN', 'user_company.role', $roles]);
        $results = $query->all();
        return !empty($results);
    }

    public static function queryGetUsersForCompany($company_id)
    {
        return self::find()->joinWith('company')->joinWith('user')
            ->where(['like', 'user.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['like', 'company.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['=', 'company.company_id', $company_id])
            ->andWhere(['IN', 'user_company.role', [Constants::ROLE_OWNER_DB, Constants::ROLE_SUPERVISOR_DB, Constants::ROLE_MEMBER_DB]]);
    }

    public static function queryGetAllUsersForCompany($company_id)
    {
        return self::find()->joinWith('company')->joinWith('user')
            ->where(['IN', 'user.status', [Constants::STATUS_ACTIVE_DB, Constants::STATUS_INACTIVE_DB]])
            ->andWhere(['like', 'company.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['=', 'company.company_id', $company_id])
            ->andWhere(['IN', 'user_company.role', [Constants::ROLE_OWNER_DB, Constants::ROLE_SUPERVISOR_DB, Constants::ROLE_MEMBER_DB]]);
    }

    public static function getDetailedUserInfoForCompany($user_id, $company_id)
    {
        $query = self::find()->joinWith('company')->joinWith('user')
            ->where(['IN', 'user.status', [Constants::STATUS_ACTIVE_DB, Constants::STATUS_INACTIVE_DB]])
            ->andWhere(['like', 'company.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['=', 'company.company_id', $company_id])
            ->andWhere(['=', 'user.user_id', $user_id])
            ->andWhere(['IN', 'user_company.role', [Constants::ROLE_OWNER_DB, Constants::ROLE_SUPERVISOR_DB, Constants::ROLE_MEMBER_DB]]);
        $results = $query->all();
        if (empty($results)) {
            return null;
        }
        return $results[0];
    }

    public static function getUsersForCompany($company_id)
    {
        $query = self::queryGetUsersForCompany($company_id);
        $results = $query->all();
        if (empty($results)) {
            return null;
        }
        return $results;
    }

    public static function queryGetCompaniesForUser()
    {
        $user_id = Yii::$app->user->identity->user_id;
        return self::find()->joinWith('company')->joinWith('user')
            ->where(['like', 'user.status', Constants::STATUS_ACTIVE_DB])
            ->andWhere(['=', 'user.user_id', $user_id])
            ->andWhere(['like', 'user_company.status', Constants::STATUS_ACTIVE_DB]);
    }

    public static function getCompaniesForUser()
    {
        return self::queryGetCompaniesForUser()->asArray()->all();
    }

    public static function findCompanyForUser($company_id)
    {
        $query = self::queryGetCompaniesForUser()
            ->andWhere(['=', 'company.company_id', $company_id]);
        $results = $query->all();
        if (empty($results)) {
            return null;
        }
        return $results[0];
    }

    public static function findUserCompanyRow($user_id, $company_id)
    {
        $query = self::find()->where(['=', 'user_id', $user_id])
            ->andWhere(['=', 'company_id', $company_id]);
        $results = $query->all();
        if (empty($results)) {
            return null;
        }
        return $results[0];
    }
}
