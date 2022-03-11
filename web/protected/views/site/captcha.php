<?php
/* @var $this SiteController */

/* @var $model CaptchaForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Captcha';
$this->breadcrumbs = array(
    'Captcha',
);
?>

<h1>Captcha</h1>

<p>Please fill out the following form with your Google Authenticator secret code:</p>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => CHtml::normalizeUrl(array('/site/captcha')),
        'id' => 'captcha-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    )); ?>

    <?php
    echo $form->errorSummary($model); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <div class="row">
        <?php
        echo $model->qrCodeUrl; ?>
    </div>


    <div class="row">
        <?php
        echo $form->labelEx($model, 'secret'); ?>
        <?php
        echo $form->textField($model, 'secret'); ?>
        <?php
        echo $form->error($model, 'secret'); ?>
    </div>

    <div class="row" hidden>
        <?php
        echo $form->textField($model, 'email'); ?>

    </div>

    <div class="row" hidden>
        <?php
        echo $form->passwordField($model, 'password'); ?>
    </div>

    <div class="row rememberMe" hidden>
        <?php
        echo $form->checkBox($model, 'rememberMe'); ?>
    </div>

    <div class="row buttons">
        <?php
        echo CHtml::submitButton('Captcha'); ?>
    </div>

    <?php
    $this->endWidget(); ?>
</div><!-- form -->
