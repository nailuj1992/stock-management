<?php

namespace tests\unit\models;

use app\models\UserLogin;

class UserTest extends \Codeception\Test\Unit
{
    public function testFindUserById()
    {
        verify($user = UserLogin::findIdentity(100))->notEmpty();
        verify($user->email)->equals('admin');

        verify(User::findIdentity(999))->empty();
    }

    public function testFindUserByAccessToken()
    {
        verify($user = UserLogin::findIdentityByAccessToken('100-token'))->notEmpty();
        verify($user->email)->equals('admin');

        verify(User::findIdentityByAccessToken('non-existing'))->empty();        
    }

    public function testFindUserByUsername()
    {
        verify($user = UserLogin::findByUsername('admin'))->notEmpty();
        verify(User::findByUsername('not-admin'))->empty();
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser()
    {
        $user = UserLogin::findByUsername('admin');
        verify($user->validateAuthKey('test100key'))->notEmpty();
        verify($user->validateAuthKey('test102key'))->empty();

        verify($user->validatePassword('admin'))->notEmpty();
        verify($user->validatePassword('123456'))->empty();        
    }

}
