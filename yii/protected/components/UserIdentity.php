<?php

class UserIdentity extends CUserIdentity
{
    private $_id;
    public function authenticate()
    {
        $record = User::model()->findByAttributes(array('username'=>$this->username));
        if ($record === null) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        	$this->errorMessage='Invalid Username';
        } else if (!$record->verifyPassword($this->password)) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
            $this->errorMessage='Invalid Password';
        } else
        {
            $this->_id=$record->user_id;
            $this->setState('username', $record->username);
            $this->errorCode=self::ERROR_NONE;
            $this->errorMessage='No Error';
        }
        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}