<?php
class SiteConfigController extends DaController {
  public function accessRules(){
      return array(
          array('allow',  // allow all users to perform 'list' and 'show' actions
                'actions'=>array('index'),
                'users'=>array('@') ),
          
          array('allow', // allow authenticated user to perform 'update' and 'delete' actions
                'actions'=>array('create','update', 'delete', 'testconnection', 'restore'),
                'users'=>array('@'),
                'expression'=> '$user->isAdmin()',//$isOwnerOrAdmin,
          ),
          
          array('deny', 
                'users'=>array('*')),
      );
   }
	public function actionCreate(){
   
    $model = new SiteConfig();
    if(isset($_POST["SiteConfig"])){
      $model->setAttributes($_POST["SiteConfig"]);
      if($model->save()){
        Yii::app()->user->setFlash("success", "Database configuration has been saved successfully");
        $this->redirect($this->createUrl("index"));
      }else
        Yii::app()->user->setFlash("error", "Failed to save database configuration"); 
    }
    $this->render("create", array("model" => $model));
  }
  
  public function actionIndex(){
    $model = new SiteConfig();
    
    $criteria = new CDbCriteria();
    $criteria->order = " id DESC " ;
    
    $itemCount = $model->count($criteria);
    
    $pages = new CPagination($itemCount) ;   
    $pages->pageSize = DaConfig::PAGE_SIZE ;
    $pages->applyLimit($criteria);
    
    $sites = $model->findAll($criteria);
    $this->render("index", array("sites" => $sites, "pages" => $pages ));
  }
  
  public function actionDelete($id){
    try{
      $model = $this->loadModel($id);
      $model->delete();
      Yii::app()->user->setFlash("success", "Database configuration has been removed successfully");
    }
    catch(Exception $ex){
      Yii::app()->user->setFlash("error", "Failed to remove site with: <br /> [ <b>message</b>] : ". $ex->getMessage()
              ."<br />[<b>Code</b>] : " . $ex->getCode() );
    }
    $this->redirect($this->createUrl("siteconfig/index"));
  }

  public function actionUpdate($id){
    try{
      $model = $this->loadModel($id);      
      if(isset($_POST["SiteConfig"])){
        $model->setAttributes($_POST["SiteConfig"]);
        if($model->save()){
          Yii::app()->user->setFlash("success", "Database configuration has been updated successfully");
          $this->redirect($this->createUrl("index"));
        }
        else
          Yii::app()->user->setFlash("error", "Failed to update database configuration");
      }
      $this->render("update", array("model" => $model));
      
    }
    catch (Exception $ex){
      throw  new CHttpException("Invalid database configuration");
    }
  }
  
  public function actionTestConnection(){
    $siteconfig = $_GET["SiteConfig"];
    $db = new DaDbConnection( );
    try{
      $db->connect($siteconfig["host"], $siteconfig["user"], $siteconfig["password"] , $siteconfig["db"]);
      echo "";
    }
    catch(Exception $ex){
      echo nl2br($ex->getMessage()); 
    }
  }
  
  public function actionRestore(){
     $siteconfig_id = (int)$_GET["id"];
     $site = new SiteConfig();
     $siteconfig = $site->findByPk($siteconfig_id);//   SiteConfig::model()->findByPk($siteconfig_id);
     $errors = array("message"=>""); 
     
     if($siteconfig){
        if($siteconfig->status == SiteConfig::PENDING){
          $errors["message"] = "Error : " . $siteconfig->fullName() ;
        }
        else{
          $lastBackup = $siteconfig->lastBackUp(false);
          if ($lastBackup && $lastBackup->restorable()){
            $backupAttrs = $lastBackup->attributes;
            $siteAttrs = $siteconfig->attributes;

            $db = new DaDbConnection();
            $startTime = microtime(true);
            $lastBackup->status = Backup::PENDING;
            $lastBackup->save();
            $connection = null;
            $file =  DaConfig::pathDataStore().$backupAttrs["filename"] ;
            $errors = $db->restoreFromBakFile($siteAttrs["host"], $siteAttrs["user"], 
                        $siteAttrs["password"], $siteAttrs["db"],$file ,$connection );

            $endTime =  microtime(true);
            if(count($errors)){
              $lastBackup->status = Backup::FAILED;
              $lastBackup->reason = $errors["message"];
            }  else {
              $lastBackup->status = Backup::SUCCESS;
            }
            $lastBackup->duration = $endTime-$startTime;
            $lastBackup->save();
            @unlink($file);
          }
          else
            $errors["message"] = "No back up to restore" ;
        }
     }
     else
       $errors["message"] = "Site not found";       
     
     if(count($errors)){
       Yii::app ()->user->setFlash("error", "Failed to restore :". $errors["message"]);
     }
     else{
       try{
         $siteconfig->updateSiteAttributes();
         Yii::app()->user->setFlash("success", "Database has been restored successfully");
       }
       catch(DaInvalidSiteDatabaseException $ex){
         $errors["message"] = $ex->getMessage();
         Yii::app()->user->setFlash("error", $ex->getMessage());
       }
     } 
     echo json_encode($errors);
  }
  
  public function loadModel($id){
		$model = SiteConfig::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}