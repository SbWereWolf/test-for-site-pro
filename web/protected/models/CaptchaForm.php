<?php

use PragmaRX\Google2FAQRCode\Google2FA;

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CaptchaForm extends CFormModel
{
    public $email;
    public $password;
    public $rememberMe;
    public $secret;
    public $qrCodeUrl;

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('email, password, secret', 'required'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'secret' => 'Google Authenticator secret code',
        );
    }

    public function verify()
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity(
                $this->email,
                $this->password
            );
            $this->_identity->authenticate();
        }

        $customer = null;
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $customer = Customer::model()
                ->find(
                    'email=:email',
                    array(':email' => $this->email)
                );
        }
        if (!$customer) {
            $this->addError('email', 'email not found');
        }

        $result = false;
        if ($customer) {
            $google2fa = new Google2FA();
            $result = $google2fa->verifyKey(
                $customer->secret,
                $this->secret
            );
        }
        if (!$result) {
            $this->addError('secret', 'secret is invalid');
        }
        if ($result) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
        }

        return $result;
    }
}
