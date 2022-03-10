<?php
/* @var $this SiteController */

/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Registration';
$this->breadcrumbs = array(
    'Registration',
);
?>

<h1>Registration</h1>

<p>Please fill out the following form with your new account credentials:</p>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'registration-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    )); ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'email'); ?>
        <?php
        echo $form->textField($model, 'email'); ?>
        <?php
        echo $form->error($model, 'email'); ?>
    </div>

    <div class="row">
        <?php
        echo $form->labelEx($model, 'password'); ?>
        <?php
        echo $form->passwordField($model, 'password'); ?>
        <?php
        echo $form->error($model, 'password'); ?>
    </div>

    <div class="row buttons">
        <?php
        echo CHtml::submitButton('Register'); ?>
    </div>

    <?php
    $this->endWidget(); ?>
</div><!-- form -->
