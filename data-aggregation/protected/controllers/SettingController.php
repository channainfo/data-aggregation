<?php
 class SettingController extends DaController {
   public function accessRules(){
      return array(
          array('allow', // allow authenticated user to perform 'update' and 'delete' actions
                'actions'=>array('export'),
                'users'=>array('@'),
                'expression'=> '$user->isAdmin()',//$isOwnerOrAdmin,
          ),
          
          array('deny', 
                'users'=>array('*')),
      );
   }
   public function actionExport(){
     $settings = DaConfig::importSetting();
     if(isset($_POST["Setting"]["export"])) {
       Setting::save($_POST["Setting"]["export"]);
       Yii::app()->user->setFlash("success", "Setting has been saved");
       $this->redirect($this->createUrl("export"));
     }
     $this->render("export", array("settings" => $settings));
   }
 }