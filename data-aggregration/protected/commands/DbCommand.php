<?php
  //yiic <command-name> <parameters>
  class DbCommand extends CConsoleCommand{
    
     public function actionSeed(){
    
      $admin_group_id = 1;
      
      $groups = array(
          array("name" => "Administrator", "description" => "Administrator of system" , "id" => $admin_group_id ),
          array("name" => "Viewer", "description" => "TODO: clarify later", "id" => 2)
      );
      
      
      $users = array(
          array("name" => "Administrator", "login" => "admin", "password" => "123456" , 
              "password_repeat" => "123456","active" => 1, "group_id" => $admin_group_id ) 
      );
      
      
      foreach($groups as $group){
         $model = new Group();
         $model->setAttributes($group);
         $model->id = $group["id"];
         
         if($model->save())
           echo "\n  '{$model->name}' has been created";
         else
           echo "\n  '{$model->name}' could not be created";
      }
      
      foreach($users as $user) {
        $model = new User();
        $model->setAttributes($user);
        if($model->save())
           echo "\n  '{$model->name}' has been created";
         else
           echo "\n  '{$model->name}' could not be created";
      }
    }
    
    public function actionCleanSeed(){
      $count = User::model()->deleteAll();
      echo "\nremove '$count' users ";
      $count = Group::model()->deleteAll();
      echo "\nremove '$count' groups ";
      
    }
  }