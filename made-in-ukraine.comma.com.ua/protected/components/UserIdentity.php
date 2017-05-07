<?php

class UserIdentity extends CUserIdentity
{
	private $_id;
        
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
    {
        $record=Editors::model()->findByAttributes(array('email'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($record->password !== md5($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $record->last_login_time = date('Y-m-d H:i:s');
            $record->update(array('last_login_time'));
            
            $userRole = Editors::model()->getUserRole($record->id); 


            $this->_id = $record->id;
            $this->setState('name', $record->fullname);
            $this->setState('email', $record->email); 
            $this->setState('userRole', $userRole); 

            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
}