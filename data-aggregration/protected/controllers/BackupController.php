<?php
 class BackupController extends DaController{
   public function actionIndex(){
     
     $model = new Backup();
     
     $criteria = new CDbCriteria();
     $criteria->order = " modified_at DESC";
     $criteria->condition = "siteconfig_id = ". intval($_GET["siteconfig_id"]);
     
     $totalCount = $model->count($criteria);
     
     $pages = new CPagination($totalCount);
     $pages->pageSize = DaConfig::PAGE_SIZE;
     $pages->applyLimit($criteria);
     
     $backups = $model->findAll($criteria);
     
     $siteconfig = SiteConfig::model()->findByPk((int)$_GET["siteconfig_id"]);
     $this->render("index", array("backups" => $backups, "pages" => $pages, "siteconfig" => $siteconfig, "model" => $model));
     
   }
   
   public function actionCreate(){
     $siteconfig = $this->getSiteConfig((int)$_GET["siteconfig_id"]);
     $model = new Backup();
     
     if(isset($_POST["Backup"])){
       $model->setAttributes($_POST['Backup']);
       $file = CUploadedFile::getInstance($model,'filename');
       $filename = DaConfig::generateFile($file->name); 
       $model->setAttributes(array("filename"=> DaConfig::pathDataDb().$filename, "siteconfig_id" =>(int)$_GET["siteconfig_id"] ));
       
       if($model->validate() && $file->saveAs(DaConfig::pathDataStore().$filename)){
          if($model->save()){
            Yii::app()->user->setFlash("success", "Backup has been added successfully");
            $this->redirect($this->createUrl("backup/index", array("siteconfig_id" => $_GET["siteconfig_id"])));
          }
          else {
              Yii::app()->user->setFlash("error", "Failed to save backup");
          }
       }
     }
     
     $this->render("create", array("siteconfig" => $siteconfig, "model" => $model ));
   }
   
   private function getSiteConfig($siteconfig_id){
    $siteconfig =  SiteConfig::model()->findByPk($siteconfig_id);
    if($siteconfig == null)
      throw new CHttpException("Site not valid");
    return $siteconfig ;
   }
 }
?>
