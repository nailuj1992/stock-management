<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $user_id
 * @property string $email
 * @property string $password
 * @property string $repassword
 */
class UserPassword extends \yii\db\ActiveRecord
{
    public $email;
    public $password;
    public $repassword;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'email', 'password', 'repassword'], 'required'],
            [['email', 'password', 'repassword'], 'string', 'max' => 100],
            [
                'password',
                'match',
                'pattern' => '/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/',
                'message' => Yii::t(TextConstants::APP, TextConstants::MESSAGE_PASSWORD_VALIDATION)
            ],
            [
                'repassword',
                'compare',
                'compareAttribute' => 'password',
                'message' => Yii::t(TextConstants::APP, TextConstants::MESSAGE_PASSWORDS_NOT_MATCH),
            ],
            [['email'], 'email'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t(TextConstants::USER, TextConstants::USER_MODEL_ID),
            'email' => Yii::t(TextConstants::ATTRIBUTE, TextConstants::ATTRIBUTE_MODEL_EMAIL),
            'password' => Yii::t(TextConstants::USER, TextConstants::USER_MODEL_PASSWORD),
            'repassword' => Yii::t(TextConstants::USER, TextConstants::USER_MODEL_REPASSWORD),
        ];
    }

}
