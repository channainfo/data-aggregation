<?php
 /**
  * 
  */
 class PasswordChangeForm extends CFormModel{
   public $old_password;
   public $password;
   public $password_repeat;
   public $user_id ;


   public  function rules(){
     return array(
         array("old_password, password, password_repeat", "required"),
         array("password" , "compare"),
         array("user_id", "safe"),
         array("old_password", "check"),
     );
   }
   
   public function attributeLabels() {
     return array(
         "old_password" => "Current Password",
         "password" => "New password",
         "password_repeat" => "Password Repeat"
     );
   }
   
   public function check($attribute, $params){
     
     if(!$this->hasErrors()){
        $user = User::model()->findByPk($this->user_id);
        if($user == null)
          throw new CHttpException("Invalid user");
        else{
            if($user->encrypt($this->old_password, $user->salt) != $user->password){
              $this->addError("old_password", "Incorrect password");
            }
        }
     }
   }
   
   
 }
