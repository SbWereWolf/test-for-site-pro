<?php

use PragmaRX\Google2FA\Google2FA;

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegistrationForm extends CFormModel
{
    public $email;
    public $password;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('email, password', 'required'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'email' => 'Google account email, will be used as login',
            'password' => 'Password for log in this site',
        );
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return bool whether login is successful
     */
    public function register()
    {
        $result = $this->hasErrors();

        if (!$result) {
            $user = new Customer();
            $user->attributes = $this->attributes;

            $google2fa = new Google2FA();
            $secret = $google2fa->generateSecretKey();
            $user->secret = $secret;

            $result = $user->save();
        }

        if ($result) {
            $identity = new UserIdentity(
                $user->email,
                $user->password
            );
            Yii::app()->user->login($identity, 0);
        }

        return $result;
    }
}
