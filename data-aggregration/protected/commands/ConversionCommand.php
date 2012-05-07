<?php

 class ConversionCommand extends CConsoleCommand {
    private $startTime;
    private $endTime ;
   
    public function beforeAction($action, $params) {
      DaTool::p("\n Action {$action} running ") ;
      $this->startTime = microtime(true);
      return parent::beforeAction($action, $params);
    }
    
    public function afterAction($action, $params) {
      $this->endTime = microtime(true);
      $duration = $this->endTime -  $this->startTime ;
      DaTool::p("\n Finished running {$action}");
      DaTool::p(" - Duration : {$duration}" );
      DaTool::p(" - Memory : ". floatval(memory_get_peak_usage(true)/(1024*1024)));
      return parent::afterAction($action, $params);
    }    
    
    public function actionStart($conversionId){
      $conversionId = new DaConversion($conversionId, Yii::app()->db);
      $conversionId->start();
    }
  }