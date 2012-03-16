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
     $siteconfig_id = (int)$_GET["siteconfig_id"];
     $siteconfig = $this->getSiteConfig($siteconfig_id);
     $backup = $siteconfig->lastBackUp(false);
     
     if($backup and $backup->restorable()){
       $this->render("create", array("backup" => $backup, "siteconfig" => $siteconfig));
     }
     else{
        $model = new Backup();
        if(isset($_POST["Backup"]) ){
          if( $_FILES["Backup"]["error"]["filename"] !== UPLOAD_ERR_OK){
            Yii::app()->user->setFlash("error", "Please choose the file");
          }
          else{
              $model->setAttributes($_POST['Backup']);
              $file = CUploadedFile::getInstance($model,'filename');
              $filename = DaConfig::generateFile($file->name); 
              $model->setAttributes(array("filename"=>$filename, "siteconfig_id" =>(int)$_GET["siteconfig_id"] ));

              if($model->validate() && $file->saveAs(DaConfig::pathDataStore().$filename)){
                  if($model->save()){
                    Yii::app()->user->setFlash("success", "Restoration has been saved successfully");
                    $this->redirect($this->createUrl("backup/index", array("siteconfig_id" => $_GET["siteconfig_id"])));
                  }
                  else 
                      Yii::app()->user->setFlash("error", "Failed to save backup file");
              }
          }
        }
        $this->render("create", array("siteconfig" => $siteconfig, "model" => $model ));
     }
   }
   public function actionDelete(){
      $backup = Backup::model()->findByPk((int)$_GET["id"]);
      if($backup){
        $backup->delete();
        Yii::app()->user->setFlash("success", "Import history has been deleted" );
      }
      else{
        throw new CHttpException("Import history with id: {$_GET["id"]} not found");
      }
      $this->redirect($this->createUrl("backup/index", array("siteconfig_id" => $backup->siteconfig_id)));
   }
   
   
   /**
    *
    * @param integer $siteconfig_id
    * @return SiteConfig
    * @throws CHttpException 
    */
   private function getSiteConfig($siteconfig_id){
    $siteconfig =  SiteConfig::model()->findByPk($siteconfig_id);
    if($siteconfig == null)
      throw new CHttpException("Site not valid");
    return $siteconfig ;
   }
 }
?>
