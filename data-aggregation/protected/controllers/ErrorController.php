<?php
 class ErrorController extends Controller{
   public $layout = "//layouts/error" ;
   public function actionError(){
     $error=Yii::app()->errorHandler->error;
     if($error)
        $this->render('error', array("error" => $error));
   }
 }
