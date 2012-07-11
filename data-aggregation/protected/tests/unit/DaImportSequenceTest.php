<?php
/**
 * @property SiteConfig $site 
 * @property ImportSiteHistory $import
 */
 class DaImportSequenceTest extends CDbTestCase {
   
   public $site;
   public $import ;
   
   public function __construct() {
     $this->cleanUp();
   }
   
   public function setUp() {
     parent::setUp();
     $this->createSite2();
   }
   
   public function createSite2(){
     $this->site = new SiteConfig();
     $this->site->attributes = array(
         "code" => "1901",
         "name" => "site2",
         "db" => "site2",
         "user" => "sa",
         "password" => "123456",
         "host" => "localhost"
     );
     $this->site->save();     
     $this->import = new ImportSiteHistory();
     $this->import->attributes = array(
         "status" => ImportSiteHistory::START,
         "siteconfig_id" => $this->site->id 
     );
     $this->import->save() ;
   }

  public function testImportAiMain(){
    $this->removeRejectPatients("tbleimain");
    
    $daImporter = new DaImporter(Yii::app()->db);
    $daImporter->truncate(true);
    
    $import = new DaImportSequence(Yii::app()->db, $this->site->code);
    
    $import->importIMain("tblaimain");
    
    $patients = $this->getInsertedPatients("tblaimain", $this->site->code);
    $rejectPatients = $import->rejectPatients("tblaimain");
    
    $total = $import->getTotalPatientIter();
    
    echo "\n Info: total inserted: ".count($patients)." total rejected: ". count($rejectPatients)." total: $total";
    echo "\n patient :" ;
    print_r($patients);
    
    
//    $this->assertEquals( count($patients), 5);
//    $this->assertEquals( $this->strEqual($patients[0]["CLinicID"], "000002"), true);
//    $this->assertEquals( $this->strEqual($patients[1]["CLinicID"], "000003"), true);
//    $this->assertEquals( $this->strEqual($patients[2]["CLinicID"], "000004"), true);
//    $this->assertEquals( $this->strEqual($patients[3]["CLinicID"], "000008"), true);
//    $this->assertEquals( $this->strEqual($patients[4]["CLinicID"], "0000010"), true);
    
    
    
    $totalPatients = array(
        "000002" => array(
            "tblavmain" => array( 
                                  "tblavlostdead" => 2 , 
                                  "tblavarv" => 2 , 
                                  "tblAVTBdrugs" => 1, 
                                  "tblappoint" =>1, 
                                  "tblAVOIdrugs" => 2, 
                                  "tblAVTB" => 1 ),
            "tblaimain" => array( "tblavmain" => 2, 
                                  "tblavlostdead" => 2,
                                  "tblaiothermedical" => 3, 
                                  "tblart" => 1, 
                                  "tblAIIsoniazid" => 1,
                                  "tblAIARVTreatment" => 1,
                                  "tblAIDrugAllergy" => 1, 
                                  "tblAICotrimo" => 1, 
                                  "tblAIOthPasMedical" => 1,
                                  "tblAIFamily" => 3, 
                                  "tblAITBPastMedical" => 1,
                                  "tblAIFluconazole" => 1, 
                                  "tblAITraditional" =>1,
                                  "tblPatientTest" => 4,
                                ),
            "tblpatienttest" => array(
                                "tblTestCXR" => 7,
                                "tbltestAbdominal" => 3 
             )
            ),
        "000003" => array(
            "tblavmain" => array( 
                                  "tblavlostdead" => 1 , 
                                  "tblavarv" => 1 , 
                                  "tblAVTBdrugs" => 0, 
                                  "tblappoint" =>1, 
                                  "tblAVOIdrugs" => 2, 
                                  "tblAVTB" => 2 ),
            "tblaimain" => array( "tblavmain" => 9, 
                                  "tblavlostdead" => 1,
                                  "tblaiothermedical" => 0, 
                                  "tblart" => 0, 
                                  "tblAIIsoniazid" => 1,
                                  "tblAIARVTreatment" => 0,
                                  "tblAIDrugAllergy" => 1, 
                                  "tblAICotrimo" => 1, 
                                  "tblAIOthPasMedical" => 1,
                                  "tblAIFamily" => 4, 
                                  "tblAITBPastMedical" => 1,
                                  "tblAIFluconazole" => 1, 
                                  "tblAITraditional" =>1,
                                  "tblPatientTest" => 3,
                                ),
            "tblpatienttest" => array(
                                "tblTestCXR" => 4,
                                "tbltestAbdominal" => 2 
             )
            ),
    );
    
//    foreach($totalPatients as $clinicid => $record){
//      foreach($record["tblavmain"] as $table => $count ){
//        $rows = $this->getAVChildRecords($table, $clinicid, $this->site->code);
//        $this->assertEquals(count($rows), $count);
//        
//      }
//      foreach($record["tblaimain"] as $table => $count){
//        $rows = $this->getAiChildRecords($table, $clinicid, $this->site->code);
//        $this->assertEquals(count($rows), $count);
//      }
//      
//      foreach($record["tblpatienttest"] as $table => $count){
//        $rows = $this->getPatientTestChildRecords($table, $clinicid, $this->site->code);
//        $this->assertEquals(count($rows), $count);
//      }
//    }
    
    
    /** success patients 
     * 000002,
     * ---AVMain
     *     2:tblavmain , 1:tblavlostdead, 2:tblavarv, 1:tblAVTBdrugs, 1:tblappoint, 2:tblAVOIdrugs, 0:tblAVTB 
     * ---AiMain
     *     3:tblaiothermedical, 1:tblart, 1:tblAIIsoniazid, 1:tblAIDrugAllergy, 1:tblAICotrimo
     *     1:tblAIOthPasMedical, 2:tblAIFamily, 1:tblAITBPastMedical, 1:tblAIFluconazole, 1:tblAITraditional       
     * 000003, 
     * 000004, 
     * 000008,
     * 000010 
     *  
     */
     
    
    
    
    
    
    
    
    
    
    echo "\n\n reject ";
    //print_r($rejectPatients);
    
    foreach($rejectPatients as $rejectPatient){
      $p = $this->unserializePatient($rejectPatient);

      echo "\n error patient ";
      print_r($p["record"]);
      echo "\n error message ";
      print_r($p["message"]);
      
      
      
      
    }
//    $this->assertEquals(count($rejectPatients),5);
//    
//    $rejectPatient = $this->unserializePatient($rejectPatients[0]);
//    $this->assertEquals( $this->strEqual($rejectPatient["record"]["CLinicID"],"00001"),true);
//    $this->assertEquals(preg_match("/DateFirstVisit/i", $rejectPatient["message"]["0"]),1);
//    $this->assertEquals( $this->strEqual($rejectPatient["err_records"]["tblaimain"][0]["CLinicID"],"00001"),true);
//    
//    $rejectPatient = $this->unserializePatient($rejectPatients[1]);
//    $this->assertEquals( $this->strEqual($rejectPatient["record"]["CLinicID"],"00005"),true);
//    
//    $rejectPatient = $this->unserializePatient($rejectPatients[2]);
//    $this->assertEquals( $this->strEqual($rejectPatient["record"]["CLinicID"],"00006"),true);
//    
//    $rejectPatient = $this->unserializePatient($rejectPatients[3]);
//    $this->assertEquals( $this->strEqual($rejectPatient["record"]["CLinicID"],"00007"),true);
//    
//    $rejectPatient = $this->unserializePatient($rejectPatients[4]);
//    $this->assertEquals( $this->strEqual($rejectPatient["record"]["CLinicID"],"00009"),true);
    
    /** rejectPatients
     * 000001 : tblaimain DateFirstVisit 1900
     * 000005 : tblavlostdead status
     * 000006 : tblaimain  Artnumber 1234567890
     * 000007 : tblavarv   Arv = [blah] invalid
     * 000009 : tblavlostdead 
     * 
     */
  }
  
  public function testImportEIMain(){
//     $this->removeRejectPatients("tblaimain");
//     
//     $import = new DaImportSequence(Yii::app()->db, $this->site->code );
//     $import->importIMain("tbleimain");
//     
//     $rejectPatients = $import->rejectPatients("tbleimain");
//     $patients = $this->getInsertedPatients("tbleimain", $this->site->code);
//     echo "\n\npatient :" ;
//     print_r($patients);
//     echo "\n\nreject :" ;
//     print_r($rejectPatients);
     
     
//     $this->assertEquals(count($patients), 3);
//
//     $this->assertEquals($patients[0]["ClinicID"], "123");
//     $this->assertEquals($patients[1]["ClinicID"], "1234");
//     $this->assertEquals($patients[1]["ClinicID"], "1234567890");
//     
//     $this->assertEquals(count($rejectPatients), 6);
//     
//     $patient1 = $this->unserializePatient($rejectPatients[0]);
//     $this->assertEquals( preg_match("/Year should not be 1900/i", $patient1["message"][0])> 0 , true );
//     $this->assertEquals( $this->strEqual($patient1["err_records"]["tbleimain"][0]["ClinicID"] , "12"), true );
//     $this->assertEquals( $this->strEqual($patient1["record"]["ClinicID"], "12" ), true);
//     
//     $patient2 = $this->unserializePatient($rejectPatients[1]);
//     $this->assertEquals( preg_match("/Year should not be 1900/i", $patient2["message"][0])> 0 , true );
//     $this->assertEquals( $this->strEqual($patient2["err_records"]["tbleimain"][0]["ClinicID"] , "12345"), true );
//     $this->assertEquals( $this->strEqual($patient2["record"]["ClinicID"], "12345" ), true);
//     
//     $patient3 = $this->unserializePatient($rejectPatients[2]);
//     $this->assertEquals( preg_match("/Status/i", $patient3["message"][0])> 0 , true );
//     $this->assertEquals( $this->strEqual($patient3["err_records"]["tblevlostdead"][0]["ClinicID"] , "123456"), true );
//     $this->assertEquals( $this->strEqual($patient3["record"]["ClinicID"], "123456" ), true);
//     
//
//     $patient4 = $this->unserializePatient($rejectPatients[3]);
//     $this->assertEquals( preg_match("/\[ARV\]/i", $patient4["message"][0])> 0 , true );
//     $this->assertEquals( $this->strEqual($patient4["err_records"]["tblevarv"][0]["ARV"] , "ppp"), true );
//     $this->assertEquals( $this->strEqual($patient4["record"]["ClinicID"], "1234567" ), true);
//     
//     $patient5 = $this->unserializePatient($rejectPatients[4]);
//     $this->assertEquals( preg_match("/Status/i", $patient5["message"][0])> 0 , true );
//     $this->assertEquals( $this->strEqual($patient5["err_records"]["tblevlostdead"][0]["ClinicID"] , "12345678"), true );
//     $this->assertEquals( $this->strEqual($patient5["record"]["ClinicID"], "12345678" ), true);
//     
//     
//     
//     $patient6 = $this->unserializePatient($rejectPatients[5]);
//     $this->assertEquals( preg_match("/Patient is not under 2 years old/i", $patient6["message"][0])> 0 , true );
//     $this->assertEquals( $this->strEqual($patient6["err_records"]["tblevmain"][0]["ClinicID"] , "123456789"), true );
//     $this->assertEquals( $this->strEqual($patient6["record"]["ClinicID"], "123456789" ), true);
//     
//     $visits = $this->getVisitMain("tblevmain", $this->site->code, "123");
//     $this->assertEquals( count($visits),2);
//     $this->assertEquals( $this->strEqual($visits[0]["ClinicID"],"123"), true);
//     $this->assertEquals( $this->strEqual($visits[1]["ClinicID"],"123"), true);
//     
//     $visits = $this->getVisitMain("tblevmain", $this->site->code, "1234567890");
//     $this->assertEquals( count($visits),1);
//     $this->assertEquals( $this->strEqual($visits[0]["ClinicID"],"1234567890"), true);
//     $this->assertEquals( $this->strEqual($visits[0]["EID"],"14"), true);
//     
//     
//     $lostdeads = $this->getEvLostDead($this->site->code, "1234567890");
//     $this->assertEquals(count($lostdeads), 2);
//     $this->assertEquals($this->strEqual($lostdeads[0]["ClinicID"], "1234567890"), true);
//     $this->assertEquals($this->strEqual($lostdeads[0]["EID"], "14"), true);
//     $this->assertEquals($this->strEqual($lostdeads[0]["Status"], "dead"), true);
//     
//     $this->assertEquals($this->strEqual($lostdeads[1]["ClinicID"], "1234567890"), true);
//     $this->assertEquals($this->strEqual($lostdeads[1]["EID"], "14"), true);
//     $this->assertEquals($this->strEqual($lostdeads[1]["Status"], "lost"), true);
//     
//     $arvs = $this->getEvARV($this->site->code, 14);
//     $this->assertEquals(count($arvs), 1);
//     $this->assertEquals($arvs[0]["ARV"], "ddI");
     
   }
   
   private function strEqual($str1, $str2){
     return trim($str1) == trim($str2) ;
   }
   private function unserializePatient($patient){
      return array( "message" => unserialize($patient["message"]),
              "err_records" => unserialize($patient["err_records"]),
              "record" => unserialize($patient["record"])
           );
     
   }
   private function getEvLostDead($code, $clinicid){
     $sql = " select * from tblevlostdead WHERE ID = ? AND ClinicID = ?" ;
     $command = Yii::app()->db->createCommand($sql);
     $command->bindParam(1, $code);
     $command->bindParam(2, $clinicid);
     return $command->queryAll();
   }
   
   private function getEvARV($code, $eid){
     $sql = " select * from tblevarv WHERE ID = ? AND Eid = ?" ;
     $command = Yii::app()->db->createCommand($sql);
     $command->bindParam(1, $code);
     $command->bindParam(2, $eid);
     return $command->queryAll();
   }
   
   private function getInsertedPatients($table, $code){
     $sql = "SELECT * FROM  {$table} WHERE ID = ? " ;
     $command = Yii::app()->db->createCommand($sql);
     $command->bindParam(1, $code , PDO::PARAM_STR );
     $records = $command->queryAll();
     return $records ;
   }
   private function getVisitMain($table, $code, $clinicid){
     $sql = " select * from {$table} WHERE ID = ? AND ClinicID = ?" ;
     $command = Yii::app()->db->createCommand($sql);
     $command->bindParam(1, $code);
     $command->bindParam(2, $clinicid);
     return $command->queryAll();
   }
   
   public function cleanUp(){
     $tables = array("da_reject_patients", "da_import_site_histories", "da_siteconfigs", "tbleimain", "tblevmain" , "tblevlostdead", "tblevarv");
     $this->truncateTables($tables);
   }
   
   public function truncateTables($tables){
     $db = Yii::app()->db;
     DaDbHelper::startIgnoringForeignKey($db);
     foreach($tables as $table){
       $db->createCommand("truncate table {$table} ")->execute();
     }
     DaDbHelper::endIgnoringForeignKey($db);
   }
   
   public function removeRejectPatients($table){
     $db = Yii::app()->db ;
     $sql = "DELETE  FROM da_reject_patients WHERE tableName= ?";
     $command = $db->createCommand($sql);
     $command->bindParam(1, $table, PDO::PARAM_STR);
     $command->execute();
   }
   
   private function getAVChildRecords($table, $clinicId, $id){
     $sql = "Select * from {$table} where av_id in (SELECT av_id from tblavmain where tblavmain.clinicid = '{$clinicId}' AND id='{$id}' )" ;
     $commandX = Yii::app()->db->createCommand($sql);
//     echo "\n".$sql ;
     
//     $commandX->bindParam(1, $clinicId, PDO::PARAM_STR );
//     $commandX->bindParam(2, $id, PDO::PARAM_STR );
     
     return $commandX->queryAll();
   }
   
   private function getAiChildRecords($table, $clinicId, $id){
     $sql = "Select * from {$table} where clinicid = '{$clinicId}' AND id ='{$id}' " ;
     $commandX = Yii::app()->db->createCommand($sql);
//     echo "\n" .$sql ;
//     $commandX->bindParam(1, $clinicId, PDO::PARAM_STR );
//     $commandX->bindParam(2, $id, PDO::PARAM_STR );
     
     return $commandX->queryAll();
   }

   private function getPatientTestChildRecords($table, $clinicId, $id){
     $sql = "Select * from {$table} where testid in (SELECT testid from tblPatientTest where tblPatientTest.clinicid = '{$clinicId}' AND id='{$id}' )" ;
     $commandX = Yii::app()->db->createCommand($sql);
     return $commandX->queryAll();
   }
   
   
 }