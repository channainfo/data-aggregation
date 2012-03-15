<?php
  Yii::import("application.vendors.*");
  require_once "djjob/DJJobConfig.php";

  class ImportSiteHistoryController extends DaController{
    
    public function actionIndex(){
     
     $model = new ImportSiteHistory();
     
     $criteria = new CDbCriteria();
     $criteria->order = " modified_at DESC";
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
        Yii::app()->user->setFlash("error", "The Import has been already added on -  ". $siteconfig->lastImport().created_at); 
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
      
      $model = new ImportSiteHistory();
      $model->siteconfig_id = $siteconfig_id;
      $model->status = ImportSiteHistory::START ;
      
      if($model->save())
        Yii::app()->user->setFlash("success", "Import has been run" );
      
      else
        Yii::app()->user->setFlash("error", "Could not created import");
      
      //$command = DaConfig::webRoot()."protected/yiic import start --code={$siteconfig->code}";
      DJJob::enqueue(new DaImportSiteJob($siteconfig->code));
      
      $this->redirect($this->createUrl("importsitehistory/index", array("siteconfig_id" => $siteconfig_id)));
      
      
    }
  }