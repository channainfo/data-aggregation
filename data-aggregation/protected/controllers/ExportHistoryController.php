<?php
 Yii::import("application.vendors.*");
 require_once "djjob/DJJobConfig.php";
 
 class ExportHistoryController extends DaController {
   
   public function actionIndex(){
     $model = new ExportHistory();
     
     $criteria = new CDbCriteria();
     $criteria->order = " modified_at DESC";
     $totalCount = $model->count($criteria);
     
     $pages = new CPagination($totalCount);
     $pages->pageSize = DaConfig::PAGE_SIZE;
     $pages->applyLimit($criteria);
     
     $rows = $model->findAll($criteria);
     $this->render("index", array("rows" => $rows, "pages" => $pages));
   }
   
   public function actionView(){
     $model = ExportHistory::model()->findByPk((int)$_GET["id"]);
     $this->render("view", array("model" => $model));
   }
   
   public function actionCreate(){
     $model = new ExportHistory();
     if(isset($_POST["ExportHistory"])){
       $model->setData($_POST["ExportHistory"]);
       if($model->save()){
         Yii::app()->user->setFlash("success", "Export have been created");
         DJJob::enqueue(new DaExportSiteJob($model->id));
         $job_id = DJJob::lastInsertedJob();
         $model->job_id = $job_id ;
         $model->status = ImportSiteHistory::START ;
         $model->save();
         $this->redirect($this->createUrl("index"));
       }
       else
         Yii::app()->user->setFlash("error", "Failed to create export");
     }
     $this->render("create", array("model" => $model));
   }
   
   public function actionDelete(){
       $model = ExportHistory::model()->findByPk((int)$_GET["id"]);
       if($model){
         $model->delete();
         Yii::app()->user->setFlash("success", "Export history has been deleted" );
       }
       else{
         throw new CHttpException("Export history with id: {$_GET["id"]} not found");
       }
       $this->redirect($this->createUrl("index"));
   }
   
   public function actionDwl(){
     $model = ExportHistory::model()->findByPk((int)$_GET["id"]);    
     $fullName = DaConfig::pathDataStoreExport().$model->file ;
     $this->download($fullName);
   }
 }