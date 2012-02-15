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
     $this->render("index", array("backups" => $backups, "pages" => $pages, "siteconfig" => $siteconfig));
     
   }
   
   public function actionCreate(){
     $siteconfig = SiteConfig::model()->findByPk((int)$_GET["siteconfig_id"]);
     $model = new Backup();
     
     if(isset($_POST["Backup"])){
       $model->attributes=$_POST['Backup'];
       $model->filename = CUploadedFile::getInstance($model,'filename');
       if($model->save()){

         $model->filename->saveAs(DaConfig::pathData());
          Yii::app()->user->setFlash("success", "Backup has been added successfully");
          $this->redirect($this->createUrl("backup/index", array("siteconfig_id" => $_GET["siteconfig_id"])));
       }
        else {
          Yii::app()->user->setFlash("error", "Failed to save backup");
        }
     }
     
     $this->render("create", array("siteconfig" => $siteconfig, "model" => $model ));
   }
 }
?>
