<?php

 class ExportCommand extends CConsoleCommand {
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
    
    /**
     *
     * @param integer $id  ExportSite Id 
     */
    public function actionStart($id){
      $export = new DaExportSite(Yii::app()->db);
      $export->export($id);
    }
    /**
     *
     * @param integer $id 
     */
    public function actionConversion($id){
      $export = new DaExportSite(Yii::app()->db);
      $export->reverse($id);
    }
    
  }