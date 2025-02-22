<?php

namespace app\models;

use app\models\entities\User;

class UserLogin extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $user_id;
    public $email;
    public $password;
    public $auth_key;
    public $access_token;
    public $name;
    public $phone;
    public $address;
    public $city;
    public $status;
    public $created_by;
    public $created_at;
    public $updated_by;
    public $updated_at;
    public $updated_pwd_at;


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = User::find()
            ->where("user_id = :id", ["id" => $id])
            ->one();
        return isset($user) ? new static($user) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $users = User::find()
            ->where("access_token = :accessToken", ["accessToken" => $token])
            ->all();

        foreach ($users as $user) {
            if ($user->access_token === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        $users = User::find()
            ->where("email = :email", ["email" => $email])
            ->all();

        foreach ($users as $user) {
            if (strcasecmp($user->email, $email) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function isActive()
    {
        return Utils::isActive($this->status);
    }

    public function isInactive()
    {
        return Utils::isInactive($this->status);
    }

    public function getFullStatus()
    {
        return Utils::getFullStatus($this->status);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Utils::sha($password) == $this->password;
    }
}
