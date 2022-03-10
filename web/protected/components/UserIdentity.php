<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return bool whether authentication succeeds.
     */
    public function authenticate()
    {
        $sql = '
select null from  user
where email = :email and password = :password';
        $isExists = Customer::model()
            ->findBySql(
                $sql,
                array(
                    ':email' => $this->username,
                    ':password' => $this->password,
                )
            );
        $isExists = !empty($isExists);

        if (!$isExists) {
            $this->errorCode = static::ERROR_PASSWORD_INVALID;
        }
        if ($isExists) {
            $this->errorCode = static::ERROR_NONE;
        }

        $result = !$this->errorCode;

        return $result;
    }
}