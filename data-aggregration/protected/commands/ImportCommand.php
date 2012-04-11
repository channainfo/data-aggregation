<?php
 /**
  * @property string $code
  * @property CDbConnection $dbX
  * @property SiteConfig $siteconfig
  * @property integer $startTime
  * @property integer $endTime
  * @property array $configs
  *  
  */
 class ImportCommand extends CConsoleCommand {
    public $code = false;
    public $dbX = false;
    public $siteconfig = false ;
    public $configs = false;
    public $startTime ;
    public $endTime ;
    
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
    
    public function actionRemoveSite($code=""){
      if(!$this->confirm("\n Are you sure to remove all data related to site : {$code}"))
        return ;
      $daImporter = new DaImporter(Yii::app()->db, $code );
      $daImporter->removeSite($code);
    }

    public function actionTruncate($all=false){
      $daImporter = new DaImporter(Yii::app()->db);
      $daImporter->truncate($all);
    }

    public function actionCreate($code){
      $daImporter = new DaImporter(Yii::app()->db, $code);
      $daImporter->create();
    }
    
    public function actionStart($code){
      $daImporter = new DaImportSequence(Yii::app()->db, $code);
      $daImporter->start();
      $daImporter->rejectPatients();
    }
    
    
    
    public function actionImportFixed($code){
      $daImportSequence = new DaImportSequence(Yii::app()->db, $code);
      $daImportSequence->importTablesFixed();
    }
  }