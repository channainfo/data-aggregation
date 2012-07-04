<?php
 Yii::import("application.vendors.*");
 require_once "djjob/DJJobConfig.php";
 
 class ExportHistoryController extends DaController {
   public function accessRules(){
      return array(
          array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('index','dwl'),
                'users'=>array('@') ),
          
          array('allow', // allow authenticated user to perform 'update' and 'delete' actions
                'actions'=>array('create', 'delete','view'),
                'users'=>array('@'),
                'expression'=> '$user->isAdmin()',//$isOwnerOrAdmin,
          ),
          array('deny', 
                'users'=>array('*')),
      );
   }
   
   public function actionIndex(){
     $model = new ExportHistory();
     
     $criteria = new CDbCriteria();
     $criteria->order = " id DESC";
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
       if($model->saveAsSeparate($_POST["ExportHistory"])){
         Yii::app()->user->setFlash("success", "Export have been created");
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