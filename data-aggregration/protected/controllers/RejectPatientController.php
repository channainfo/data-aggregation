<?php
 class RejectPatientController extends DaController{
   public function actionIndex(){
     
     $model = new RejectPatient();
     
     $criteria = new CDbCriteria();
     $criteria->order = " modified_at DESC";
     $criteria->condition = "import_site_history_id = ". intval($_GET["import_site_history_id"]);
     
     $totalCount = $model->count($criteria);
     
     $pages = new CPagination($totalCount);
     $pages->pageSize = DaConfig::PAGE_SIZE;
     $pages->applyLimit($criteria);
     
     $rejectPatients = $model->findAll($criteria);
     
     $importHistory = ImportSiteHistory::model()->findByPk((int)$_GET["import_site_history_id"]);
     $this->render("index", array("rejectPatients" => $rejectPatients, "pages" => $pages, "importHistory" => $importHistory, "model" => $model));
     
   }
 }
?>
  