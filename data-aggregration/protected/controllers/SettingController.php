<?php
 class SettingController extends DaController {
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