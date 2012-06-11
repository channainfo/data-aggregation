<?php
 Yii::import("application.vendors.*");
 require_once "djjob/DJJobConfig.php";
 
 class ConversionController extends DaController {
   
   public function accessRules(){
      return array(
          array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('index','src','des'),
                'users'=>array('@') ),
          
          array('allow', // allow authenticated user to perform 'update' and 'delete' actions
                'actions'=>array('create', 'delete'),
                'users'=>array('@'),
                'expression'=> '$user->isAdmin()',//$isOwnerOrAdmin,
          ),
          
          array('deny', 
                'users'=>array('*')),
      );
   }
   
   public function verifyGroup(){
     return true ;
   } 
   public function actionIndex(){
     $model = new Conversion();
     
     $criteria = new CDbCriteria();
     $criteria->order = " modified_at DESC";
     $totalCount = $model->count($criteria);
     
     $pages = new CPagination($totalCount);
     $pages->pageSize = DaConfig::PAGE_SIZE;
     $pages->applyLimit($criteria);
     
     $rows = $model->findAll($criteria);
     $this->render("index", array("rows" => $rows, "pages" => $pages));
   }
   
   
   public function actionCreate(){
      $model = new Conversion();
      if(isset($_POST["Conversion"]) ){
        if( $_FILES["Conversion"]["error"]["src"] !== UPLOAD_ERR_OK){
          Yii::app()->user->setFlash("error", "Please choose the file");
        }
        else{
          $model->setAttributes($_POST['Conversion']);
          $file = CUploadedFile::getInstance($model,'src');
          $filename = DaConfig::generateFile($file->name); 
          $model->setAttributes(array("src"=>$filename, 
                                      "status"=> Conversion::START ,
                                      "date_start" => DaDbWrapper::now()
              
                  ));

          if($model->validate() && $file->saveAs(DaConfig::pathDataStore().$filename)){
              if($model->save()){
                DJJob::enqueue(new DaConversionJob($model->id));
                $job_id = DJJob::lastInsertedJob();
                $model->job_id = $job_id ;
                $model->date_start = DaDbWrapper::now();
                $model->save();
                
                Yii::app()->user->setFlash("success", "Conversion file has been saved");
                $this->redirect($this->createUrl("conversion/index"));
              }
              else 
                Yii::app()->user->setFlash("error", "Failed to save file");
          }
        }
      }
      $this->render("create", array("model" => $model ));
   }
   
   public function actionDelete(){
      $model = Conversion::model()->findByPk((int)$_GET["id"]);
      if($model){
        $model->delete();
        Yii::app()->user->setFlash("success", "Coversion history has been deleted" );
      }
      else{
        throw new CHttpException("Could not find any conversion history with id: {$_GET["id"]}");
      }
      $this->redirect($this->createUrl("conversion/index"));
   }
     
   public function actionSrc(){
     $model = Conversion::model()->findByPk((int)$_GET["id"]);    
     $fullName = DaConfig::pathDataStore().$model->src ;
     $this->download($fullName);
   }
   
   public function actionDes(){
     $model = Conversion::model()->findByPk((int)$_GET["id"]);    
     $fullName = DaConfig::pathDataStoreExport().$model->des ;
     $this->download($fullName);
   }
 }