<?php

use PragmaRX\Google2FAQRCode\Google2FA;

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                    "Reply-To: {$model->email}\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash(
                    'contact',
                    'Thank you for contacting us. We will respond to you as soon as possible.'
                );
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the registration page
     */
    public function actionRegistration()
    {
        $model = new RegistrationForm();

        // if it is ajax validation request
        if (
            isset($_POST['ajax'])
            && $_POST['ajax'] === 'registration-form'
        ) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['RegistrationForm'])) {
            $model->attributes = $_POST['RegistrationForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->register()) {
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        // display the registration form
        $this->render('registration', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];

            if ($model->validate() && $model->login()) {
                $customer = Customer::model()
                    ->find(
                        'email=:email',
                        array(':email' => $model->username)
                    );

                $google2fa = new Google2FA();
                $qrCodeUrl = $google2fa->getQRCodeInline(
                    "My Website",
                    $model->username,
                    $customer->secret
                );

                $captcha = new CaptchaForm();
                $captcha->qrCodeUrl = $qrCodeUrl;
                $captcha->email = $model->username;
                $captcha->password = $model->password;
                $captcha->rememberMe = $model->rememberMe;

                $this->render('captcha', array('model' => $captcha));
                Yii::app()->end();
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Displays the captcha page
     */
    public function actionCaptcha()
    {
        $model = new CaptchaForm();

        // collect user input data
        if (isset($_POST['CaptchaForm'])) {
            $model->attributes = $_POST['CaptchaForm'];
            $model->clearErrors();

            if ($model->validate() && $model->verify()) {
                $this->redirect(Yii::app()->homeUrl);
            }
        }
        if ($model->hasErrors()) {
            $this->render('captcha', array('model' => $model));
            Yii::app()->end();
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}