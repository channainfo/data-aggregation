<?php
 class RejectPatientController extends DaController{
   public function actionIndex(){
     
     $model = new RejectPatient();
     
     $criteria = new CDbCriteria();
     $criteria->order = " tableName DESC";
     $criteria->condition = "import_site_history_id = ". intval($_GET["import_site_history_id"]);
     
     $totalCount = $model->count($criteria);
     
     $pages = new CPagination($totalCount);
     $pages->pageSize = DaConfig::PAGE_SIZE;
     $pages->applyLimit($criteria);
     
     $rejectPatients = $model->findAll($criteria);
     
     $importHistory = ImportSiteHistory::model()->findByPk((int)$_GET["import_site_history_id"]);
     $this->render("index", array("rejectPatients" => $rejectPatients, "pages" => $pages, "importHistory" => $importHistory, "model" => $model));
     
   }
   public function actionExport(){
    $file = DaConfig::pathDataStoreExport()."/{$_GET["import_site_history_id"]}_reject_patient.csv" ;
    if(!file_exists($file)){
      $command = Yii::app()->db->createCommand()->select('tableName , message, record')
                  ->from('da_reject_patients p')
                  ->where('import_site_history_id=:import_site_history_id', array(':import_site_history_id'=>$_GET["import_site_history_id"]) );
      $dataReader = $command->query();

      $csv  = new DaCSV($file);
      $csv->addRow( array("clinic" => "ClinicId", "patientType" => "Patient type", "message" => "Message" ) );  

      foreach($dataReader as $record){
        $rows = array();
        if($record["record"]){
          $patient = unserialize($record["record"]);
          $rows["clinic"] = DaRecordReader::getIdFromRecord($record["tableName"], $patient);
        }
        $rows["patientType"] = RejectPatient::patientType($record["tableName"]);
        
        if($record["message"]){
          $messages = unserialize($record["message"]);
          $rows["message"] = $messages[0] ;
        }
        $csv->addRow($rows);
      }
      $csv->generate();
    }
    $this->download($file);
   }
 }
?>
  