<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
  private $_id ;
  
	public function authenticate(){
    $user = User::model()->findByAttributes(array("login" => $this->username));
    if($user === null || $user->active == User::INACTIVE){
      $this->errorCode = self::ERROR_USERNAME_INVALID;
    }
    else{
      if($user->password != $user->encrypt($this->password, $user->salt)){
        $this->errorCode = self::ERROR_PASSWORD_INVALID;
      }
      else{
        $this->_id = $user->id;
        if($user->last_login_at == null)
          $lastLoginAt = date("Y-m-d h:m:i");
        else
          $lastLoginAt = $user->last_login_at;
        
        $this->setState("lastLoginAt", $lastLoginAt ); // set CWebUser Yii::app()->user->lastLoginAt 
        $this->setState("groupId", $user->group_id);
        $this->errorCode = self::ERROR_NONE;
      }
    }
    return $this->errorCode ;
	}
  
  public function getId(){
    return $this->_id;
  }
}