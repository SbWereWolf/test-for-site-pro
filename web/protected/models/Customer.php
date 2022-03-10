<?php

/**
 * The followings are the available columns in table 'user':
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $secret
 */
class Customer extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return array(
            array('email', 'email'),
            array('email', 'unique'),
            array('email, password, secret', 'required'),
        );
    }
}