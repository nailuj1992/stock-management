<?php

namespace app\models;

use app\models\entities\UserCompany;
use app\models\Constants;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\helpers\ArrayHelper;

/**
 * Description of Utils
 *
 * @author Avuunita
 */
class Utils
{

    public static function hasPermission($rol)
    {
        return !Yii::$app->user->isGuest && Yii::$app->user->can($rol);
    }

    public static function getDateNowDB()
    {
        return date('Y-m-d H:i:s');
    }

    public static function formatDate($date)
    {
        $unixTime = strtotime($date);
        $format = Yii::$app->formatter->dateFormat;
        return date(str_replace("php:", "", $format), $unixTime);
    }

    public static function formatDateSql($date)
    {
        $unixTime = date_parse_from_format('d/m/Y', $date);
        $format = 'Y-m-d';
        $year = $unixTime['year'];
        $month = $unixTime['month'];
        $day = $unixTime['day'];
        return date($format, `$year-$month-$day`);
    }


    public static function generateTimeMark()
    {
        $now = new \DateTime();
        $year = $now->format('Y');
        $month = $now->format('m');
        $day = $now->format('d');
        $hour24 = $now->format('H');
        $minute = $now->format('i');
        $second = $now->format('s');

        $year2seconds = $year * 12 * 30 * 24 * 60 * 60;
        $month2seconds = $month * 30 * 24 * 60 * 60;
        $day2seconds = $day * 24 * 60 * 60;
        $hour2seconds = $hour24 * 60 * 60;
        $minute2seconds = $minute * 60;

        return $second + $minute2seconds + $hour2seconds + $day2seconds + $month2seconds + $year2seconds;
    }

    public static function randString($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

    public static function randPass()
    {
        return self::randString(8);
    }

    public static function getFullStatus($status)
    {
        $resp = null;
        switch ($status) {
            case Constants::STATUS_ACTIVE_DB:
                $resp = TextConstants::STATUS_ACTIVE;
                break;
            case Constants::STATUS_INACTIVE_DB:
                $resp = TextConstants::STATUS_INACTIVE;
                break;
            case Constants::STATUS_NULL_DB:
                $resp = TextConstants::STATUS_NULLED;
                break;
            case Constants::STATUS_DRAFT_DB:
                $resp = TextConstants::STATUS_DRAFT;
                break;
            default:
                $resp = "-";
                break;
        }
        return Yii::t(TextConstants::STATUS, $resp);
    }

    public static function getFullAction($action)
    {
        $resp = null;
        switch ($action) {
            case Constants::DOCUMENT_ACTION_INTENDED_INPUT_DB:
                $resp = TextConstants::DOCUMENT_ACTION_INTENDED_INPUT;
                break;
            case Constants::DOCUMENT_ACTION_INTENDED_OUTPUT_DB:
                $resp = TextConstants::DOCUMENT_ACTION_INTENDED_OUTPUT;
                break;
            default:
                $resp = "-";
                break;
        }
        return Yii::t(TextConstants::DOCUMENT, $resp);
    }

    public static function getFullApply($apply)
    {
        $resp = null;
        switch ($apply) {
            case Constants::DOCUMENT_APPLY_SUPPLIER_DB:
                $resp = TextConstants::DOCUMENT_APPLY_SUPPLIER;
                break;
            case Constants::DOCUMENT_APPLY_CUSTOMER_DB:
                $resp = TextConstants::DOCUMENT_APPLY_CUSTOMER;
                break;
            default:
                $resp = "-";
                break;
        }
        return Yii::t(TextConstants::DOCUMENT, $resp);
    }

    public static function getFullYesNo($answer)
    {
        $resp = null;
        switch ($answer) {
            case Constants::OPTION_YES_DB:
                $resp = TextConstants::OPTION_YES;
                break;
            case Constants::OPTION_NO_DB:
                $resp = TextConstants::OPTION_NO;
                break;
            default:
                $resp = "-";
                break;
        }
        return Yii::t(TextConstants::APP, $resp);
    }

    public static function getFullStatusApplication($status)
    {
        $resp = null;
        switch ($status) {
            case Constants::STATUS_APPROVED_DB:
                $resp = TextConstants::STATUS_APPROVED;
                break;
            case Constants::STATUS_REJECTED_DB:
                $resp = TextConstants::STATUS_REJECTED;
                break;
            case Constants::STATUS_PENDING_DB:
                $resp = TextConstants::STATUS_PENDING;
                break;
            default:
                $resp = "-";
                break;
        }
        return Yii::t(TextConstants::STATUS, $resp);
    }

    public static function getFullRole($role)
    {
        $resp = null;
        switch ($role) {
            case Constants::ROLE_OWNER_DB:
                $resp = TextConstants::ROLE_OWNER;
                break;
            case Constants::ROLE_SUPERVISOR_DB:
                $resp = TextConstants::ROLE_SUPERVISOR;
                break;
            case Constants::ROLE_MEMBER_DB:
                $resp = TextConstants::ROLE_MEMBER;
                break;
            default:
                $resp = "-";
                break;
        }
        return Yii::t(TextConstants::ROLE, $resp);
    }

    public static function getRoles()
    {
        $resp = [
            [
                'code' => Constants::ROLE_SUPERVISOR_DB,
                'name' => Yii::t(TextConstants::ROLE, TextConstants::ROLE_SUPERVISOR)
            ],
            [
                'code' => Constants::ROLE_MEMBER_DB,
                'name' => Yii::t(TextConstants::ROLE, TextConstants::ROLE_MEMBER)
            ]
        ];
        return ArrayHelper::map($resp, 'code', 'name');
    }

    public static function getYesNoOptions()
    {
        $resp = [
            [
                'code' => Constants::OPTION_YES_DB,
                'name' => Yii::t(TextConstants::APP, TextConstants::OPTION_YES)
            ],
            [
                'code' => Constants::OPTION_NO_DB,
                'name' => Yii::t(TextConstants::APP, TextConstants::OPTION_NO)
            ]
        ];
        return ArrayHelper::map($resp, 'code', 'name');
    }

    public static function isActive($status)
    {
        return $status === Constants::STATUS_ACTIVE_DB;
    }

    public static function isInactive($status)
    {
        return $status === Constants::STATUS_INACTIVE_DB;
    }

    public static function isApproved($status)
    {
        return $status === Constants::STATUS_APPROVED_DB;
    }

    public static function isRejected($status)
    {
        return $status === Constants::STATUS_REJECTED_DB;
    }

    public static function isPending($status)
    {
        return $status === Constants::STATUS_PENDING_DB;
    }

    public static function validatePhone($attribute)
    {
        return preg_match('/^\d{13}$/', $attribute);
    }

    public static function sha($message)
    {
        return hash('sha256', $message);
    }

    public static function isActiveUser()
    {
        return Yii::$app->user->identity->isActive();
    }

    public static function isInactiveUser()
    {
        return Yii::$app->user->identity->isInactive();
    }

    public static function validateActiveUser()
    {
        if (!self::isActiveUser()) {
            throw new ForbiddenHttpException(Yii::t(TextConstants::APP, TextConstants::MESSAGE_NON_ACTIVE_USER));
        }
    }

    public static function isOwnerOfCompany($company_id)
    {
        return UserCompany::isUserInRolesOfCompany($company_id, [Constants::ROLE_OWNER_DB]);
    }

    public static function validateOwnerOfCompany($company_id)
    {
        if (!self::isOwnerOfCompany($company_id)) {
            throw new ForbiddenHttpException(Yii::t(TextConstants::APP, TextConstants::MESSAGE_NOT_ENOUGH_PERMISSIONS));
        }
    }

    public static function isOwnerOrSupervisorOfCompany($company_id)
    {
        return UserCompany::isUserInRolesOfCompany($company_id, [Constants::ROLE_OWNER_DB, Constants::ROLE_SUPERVISOR_DB]);
    }

    public static function validateOwnerOrSupervisorOfCompany($company_id)
    {
        if (!self::isOwnerOrSupervisorOfCompany($company_id)) {
            throw new ForbiddenHttpException(Yii::t(TextConstants::APP, TextConstants::MESSAGE_NOT_ENOUGH_PERMISSIONS));
        }
    }

    public static function isMemberOfCompany($company_id)
    {
        return UserCompany::isUserInRolesOfCompany($company_id, [Constants::ROLE_MEMBER_DB]);
    }

    public static function validateMemberOfCompany($company_id)
    {
        if (!self::isMemberOfCompany($company_id)) {
            throw new ForbiddenHttpException(Yii::t(TextConstants::APP, TextConstants::MESSAGE_NOT_ENOUGH_PERMISSIONS));
        }
    }

    public static function belongsToCompany($company_id)
    {
        return UserCompany::isUserInRolesOfCompany($company_id, [Constants::ROLE_OWNER_DB, Constants::ROLE_SUPERVISOR_DB, Constants::ROLE_MEMBER_DB]);
    }

    public static function validateBelongsToCompany($company_id)
    {
        if (!self::belongsToCompany($company_id)) {
            throw new ForbiddenHttpException(Yii::t(TextConstants::APP, TextConstants::MESSAGE_NOT_ENOUGH_PERMISSIONS));
        }
    }

    public static function validateCompanySelected()
    {
        $session = Yii::$app->session;
        if (!$session->has(Constants::SELECTED_COMPANY_ID)) {
            throw new ForbiddenHttpException(Yii::t(TextConstants::APP, TextConstants::MESSAGE_SELECT_COMPANY));
        }
    }

    public static function getCompanySelected()
    {
        return Yii::$app->session->get(Constants::SELECTED_COMPANY_ID);
    }

    public static function hasCompanySelected()
    {
        return Yii::$app->session->has(Constants::SELECTED_COMPANY_ID);
    }

    public static function validateCompanyMatches($company_id)
    {
        if ($company_id != self::getCompanySelected()) {
            throw new ForbiddenHttpException(Yii::t(TextConstants::COMPANY, TextConstants::COMPANY_MESSAGE_INFO_NOT_BELONG_COMPANY));
        }
    }

    public static function isMember($role)
    {
        return $role === Constants::ROLE_MEMBER_DB;
    }

    public static function isSupervisor($role)
    {
        return $role === Constants::ROLE_SUPERVISOR_DB;
    }

    public static function isOwner($role)
    {
        return $role === Constants::ROLE_OWNER_DB;
    }

}
