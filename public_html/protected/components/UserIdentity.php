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
     * @return boolean whether authentication succeeds.
     */

    private $_id;

    const ERROR_STATUS_NOTACTIV = 4;
    const ERROR_USERGROUP_NOT_REGISTERED = 5;

    public function getId() {
        return $this->_id;
    }

    public function authenticate()
    {
        $username 	   = $this->username;
        $modelUser     = User::model()->find('user_name=:un', array(':un'=>$username));
        $modelEmployee = NULL;

        if(!isset($modelUser))
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($modelUser->password !== Encryption::encrypt($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else if($modelUser->is_active == Status::IS_STATUS_NO)
            $this->errorCode=self::ERROR_STATUS_NOTACTIV;
        else if( !($modelUser->user_type == 'ADMIN' || $modelUser->user_type == 'USER') )
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else {
            $modelEmployee  = Employee::model()->find('user_id=:user_id',array(':user_id'=>$modelUser->user_id));

            $oUserGroup  = Usergroupdetail::model()->findAll('user_id=:user_id',array(':user_id'=>$modelUser->user_id));
            $oUserGroup2 = NULL;
            foreach ($oUserGroup as $value)
                $oUserGroup2[] = $value->usergroup_id;

            if($modelEmployee == NULL )
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            else if($oUserGroup2 == NULL)
                $this->errorCode = self::ERROR_USERGROUP_NOT_REGISTERED;
            else{
                $this->_id = $modelUser->user_id;
                $this->setState('user_type', $modelUser->user_type);
                $this->setState('employee_cd',$modelEmployee->employee_cd);
                $this->setState('employee_type',$modelEmployee->employee_type);
                $this->setState('company',$modelEmployee->company_id);

                $this->setState('usergroup_id', $oUserGroup2);
                $this->errorCode=self::ERROR_NONE;
            }
        }
        return !$this->errorCode;
    }
}