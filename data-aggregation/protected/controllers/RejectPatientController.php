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
    DaConfig::mkDir(DaConfig::pathDataStoreExport());
    
    $import = ImportSiteHistory::model()->findByPk($_GET["import_site_history_id"]);
    
    $file = DaConfig::pathDataStoreExport().$import->siteconfig()->fullName().".csv" ;
    
    if(!file_exists($file) || 1){
      $command = Yii::app()->db->createCommand()->select('tableName , message, record, err_records, reject_type')
                  ->from('da_reject_patients p')
                  ->where('import_site_history_id=:import_site_history_id', array(':import_site_history_id'=>$_GET["import_site_history_id"]) )
                  ->order(" p.tableName, p.reject_type ");
      $dataReader = $command->query();

      $csv  = new DaCSV($file);
      $csv->addRow( array("clinic" => "ClinicId", "patientType" => "Patient type", "message" => "Message" , "Table", "Type" ) );  

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
        $err_records = unserialize($record["err_records"]);
        foreach($err_records as $table => $err){
          $rows["tableName"] = $table;
          break;
        }
        $rows["type"] = RejectPatient::getRejectType($record["reject_type"]);
        $csv->addRow($rows);
      }
      $csv->generate();
    }
    $this->download($file);
   }
 }