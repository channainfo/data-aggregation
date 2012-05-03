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
       
       $tables = $_POST["ExportHistory"]["table_list"]["tables"];
       $tableList = array();
       
       foreach($tables as $table => $value){
         $tableList[$table] = $_POST["ExportHistory"]["table_list"]["columns"][$table];
       }
  
       $model->reversable = $_POST["ExportHistory"]["reversable"];
       $model->date_start = DaDbWrapper::now();
       $model->all_site   = $_POST["ExportHistory"]["all_site"];
       $model->all_table  = $_POST["ExportHistory"]["all_table"];
       $model->site_text  = implode( "<br /> ", $_POST["ExportHistory"]["site_list"]);
       $model->setTableList($tableList);
       $model->setSites($_POST["ExportHistory"]["site_list"]);
       
       if($model->save()){
         Yii::app()->user->setFlash("success", "Export have been created");
         DJJob::enqueue(new DaExportSiteJob($_POST["ExportHistory"]["site_list"]));
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

     header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // some day in the past
     header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
     header("Content-type: application/octet-stream");
     header("Content-Disposition: attachment; filename={$fullName}");
     header("Content-Transfer-Encoding: binary");
     //readfile($fullName);
     $handle = fopen($fullName, "rb");
     while (!feof($handle)) {
       echo fread($handle, 8192);
       ob_flush();
     }
     fclose($handle); 
   }
 }