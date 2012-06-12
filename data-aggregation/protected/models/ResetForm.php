<?php
 /**
  * 
  */
 class ResetForm extends CFormModel{
   public $password;
   public $password_repeat;
   public $user_id ;


   public  function rules(){
     return array(
         array("password, password_repeat", "required"),
         array("password" , "compare"),
         array("user_id", "check"),
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
        if($user == null){
          throw new CHttpException("Invalid user");
        }
     }
   }
   
   
 }
