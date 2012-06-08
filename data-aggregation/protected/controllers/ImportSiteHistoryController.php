<?php
  Yii::import("application.vendors.*");
  require_once "djjob/DJJobConfig.php";

  class ImportSiteHistoryController extends DaController{
    
    public function actionIndex(){
     
     $model = new ImportSiteHistory();
     
     $criteria = new CDbCriteria();
     $criteria->order = "t.modified_at DESC";
     
     $criteria->with = array("siteconfig") ;
     $siteconfig = NULL;
     
     if(isset($_GET["siteconfig_id"])){
      $criteria->condition = "siteconfig_id = ". intval($_GET["siteconfig_id"]);
      $siteconfig = SiteConfig::model()->findByPk((int)$_GET["siteconfig_id"]);
     }
     
     $totalCount = $model->count($criteria);
     
     $pages = new CPagination($totalCount);
     $pages->pageSize = DaConfig::PAGE_SIZE;
     $pages->applyLimit($criteria);
     
     $importHistoris = $model->findAll($criteria);
     $this->render("index", array("importHistories" => $importHistoris, "pages" => $pages, "siteconfig" => $siteconfig, "model" => $model));
     
   }
   
    public function actionDelete(){
       $import = ImportSiteHistory::model()->findByPk((int)$_GET["id"]);
       if($import){
         if($import->status == ImportSiteHistory::START || $import->status == ImportSiteHistory::PENDING){
           $siteconfig = $import->siteconfig;
           $siteconfig->status = SiteConfig::INIT;
           $siteconfig->save();
         }
         $import->delete();
         Yii::app()->user->setFlash("success", "Import history has been deleted" );
       }
       else{
         throw new CHttpException("Import history with id: {$_GET["id"]} not found");
       }
       $this->redirect($this->createUrl("importsitehistory/index", array("siteconfig_id" => $import->siteconfig_id)));
    }
    
    public function actionSite(){ 
      $model = new SiteConfig();
      $criteria = new CDbCriteria();
      
      $itemCount = $model->count($criteria);
      
      $pages = new CPagination($itemCount);
      $pages->pageSize = DaConfig::PAGE_SIZE;
      $pages->applyLimit($criteria);
      
      $sites = $model->findAll($criteria);
      $this->render("site", array("sites" => $sites, "pages" => $pages));
    }
    
    public function actionImport(){
      $siteconfig_id = intval($_GET["siteconfig_id"]);
      $siteconfig = SiteConfig::model()->findByPk($siteconfig_id);
      
      if($siteconfig && $siteconfig->lastImport() && $siteconfig->lastImport()->restorable()){
        Yii::app()->user->setFlash("error", "The Import has been already added on -  ". $siteconfig->lastImport()->created_at); 
        $this->redirect($this->createUrl("importsitehistory/site"));
        return ;
      }
      elseif($siteconfig && $siteconfig->lastImport() && $siteconfig->lastImport()->inProgress()){
        Yii::app()->user->setFlash("error", "The Import has been already started and it is in progress of importing"); 
        $this->redirect($this->createUrl("importsitehistory/site"));
        return ;
      }
      
      elseif(!$siteconfig){
        Yii::app()->user->setFlash("error", "In valid site");
        $this->redirect($this->createUrl("importsitehistory/site"));
        return ;
      }
      
      DJJob::enqueue(new DaImportSiteJob($siteconfig->code));
      $job_id = DJJob::lastInsertedJob();

      $model = new ImportSiteHistory();
      $model->job_id = $job_id ;
      $model->siteconfig_id = $siteconfig_id;
      $model->status = ImportSiteHistory::START ;
      
      if($model->save()){
        Yii::app()->user->setFlash("success", "Import has been added to queue to run" );
        $siteconfig->last_imported = DaDbWrapper::now();
        $siteconfig->status = SiteConfig::START;
        $siteconfig->save();
      }
      
      else{
        DJJob::removeJob($job_id);
        Yii::app()->user->setFlash("error", "Could not created import");
      }
      
      //$command = DaConfig::webRoot()."protected/yiic import start --code={$siteconfig->code}";
      
      
      $this->redirect($this->createUrl("importsitehistory/index", array("siteconfig_id" => $siteconfig_id)));
      
    }
    
    public function actionProgress(){
      $id = (int)$_GET["importId"];
      $import = ImportSiteHistory::model()->findByPk($id);
      $infos = array();
      if($import && $import->status == ImportSiteHistory::PENDING) {
        $infos = $import->attributes ;
        if($import->attributes["total_record"] != 0 ){
         $infos["percentage"] = number_format(($import->attributes["current_record"]*100)/$import->attributes["total_record"], 2);
        }
        $infos["finished"] = false ;

      }
      else {
        $infos["finished"] = true ;
      }
      echo json_encode($infos);
    }
  }