<?php
 /**
  * 
  */
 class PasswordChangeForm extends CFormModel{
   public $old_password;
   public $password;
   public $password_repeat;
   
   public  function rules(){
     return array(
         array("old_password, password", "required"),
         array("password" , "compare"),
         array("old_password", "check", "id" => Yii::app()->user->id)
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
     
     ChTool::debug($attribute);
     ChTool::debug($params);
     return true ;
   }
   
   
 }
