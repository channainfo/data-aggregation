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
     $daImporter = new DaImporter(Yii::app()->db);
     $daImporter->truncate(true);
     
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
    $this->assertEquals(count($patients), 7);
    
    
    $rejectPatients = $import->rejectPatients("tblaimain", 0 , 100);
    $this->assertEquals(count($rejectPatients), 14);
    
    $total = $import->getTotalPatientIter();
    $this->assertEquals(count($patients)+count($rejectPatients), $total);
    
    $clinics = array("000004" ,"000005", "000006", "000007", "000008", "000010", "000016");
    
    foreach($patients as $index => $patient){
      //echo "\n result : {$patient["CLinicID"]}-expected: {$clinics[$index]}";
      $found = array_search(trim($patient["CLinicID"]), $clinics);
      $this->assertNotEquals($found, false);
    }
    
    $elements = array(
              "avmain" => array(        
                      "avlostdead" => array(0,1,2,1,0,0,0),
                      "avarv" => array(3,10,6,6,6,1,1),
                      "avlostdead" => array(0,1,0,0,2,2,0),
                      "avtb" => array(0,2,3,1,0,0,0),
                      "appoint" => array(0,3,1,1,2,0,0),
                      "avoidrugs" => array(5,0,0,4,2,0,0),
                      "avtbdrugs" => array(1,2,1,0,0,0,3),
                      ),
            
               "aimain" => array(   
                      "avmain" => array(3,5,3,4,2,1,1),       
                      "aitraditional" => array(1,1,1,1,1,1,0),
                      "art" => array(1,0,1,1,1,1,1),
                      "aiarvtreatment" => array(1,1,1,1,1,0,1),
                      "aidrugallergy" => array(0,1,1,1,0,1,1),
                      "aicotrimo" => array(1,0,1,1,0,1,1),
                      "aiothpasmedical" => array(1,1,1,1,1,0,0),
                      "aifamily" => array(3,3,4,6,1,3,0),
                      "aitbpastmedical" => array(1,1,1,1,1,1,1),
                      "aifluconazole" => array(0,0,1,1,1,1,1),
                      "aiothermedical" => array(0,1,0,0,2,0,0),
                      "aiisoniazid" => array(1,1,0,0,0,0,0),
                      "patienttest" => array(0,6,12,3,4,2,5),
                     ),
            
               "test" => array(       
                      "testcxr" => array(0,2,3,0,0,0,3),
                      "testabdominal" => array(0,1,2,4,0,2,0)
                      )
    );
    
    foreach($clinics as $clinicIndex => $clinicId){
       foreach($elements as $section => $tableList){
          foreach($tableList as $tableName => $tableValue){
              $tblName = "tbl".$tableName ;
              $expected = $tableValue[$clinicIndex];
              $result = $this->countElements($section, $tblName, $clinicId);
              //echo "\n *** result:{$result}-expected:{$expected}, table:{$tblName}, clinic:{$clinicId}";
              $this->assertEquals($result, $expected);
          }
       }
    }
    
    $elements = array(  "000001" => "Invalid [DateFirstVisit]" ,
                        "000002"=> "[tblaimain] invalid sex Gay" ,
                        "000003"=> "Invalid [ARV] . [ARV] = ['yyy'] is not in '( 3TC,ABC,AZT," ,
                        "000009"=> "[tblaimain] invalid sex TranSex" ,
                        "000011"=> "Invalid transferin. [OffYesNo=Yes] so OffTransferin should not be empty" ,
                        "000012"=> "DateStaART should not be in year 1900" ,
                        "000013"=> "ArtNumber:  does not exist in tblart with CLinicId: 000013" ,
                        "000014"=> "ArtNumber: 111122223  does not exist in tblart with CLinicId: 000014" ,
                        "000015"=> "ArtNumber: 190100015  does not exist in tblavmain with CLinicId: 000015" ,
                        "000017"=> "Invalid tblavlostdead. [Date] = '2010-09-01" ,
                        "000018"=> "Invalid tblavlostdead. [Date] = '2008-07-09 00:00:00" ,
                        "000019"=> "Invalid [ARV] . [ARV] = ['pppp'] is not in '( 3TC,ABC" ,
                        "000020"=> "Invalid [ART] number for adult: [ART]= ['1901020 " ,
                        "000021"=> "ArtNumber: 190100021  does not exist in tblart with CLinicId: 000021" 
    );
    
    foreach($rejectPatients as $index => $rejectPatient){
      $p = $this->unserializePatient($rejectPatient);
      $record = $p["record"];
      $message = $p["message"][0];
      $clinicId = trim($record["CLinicID"]);
      //echo "\n clinicid:{$record["CLinicID"]}-err:{$message}";
      $this->assertEquals(isset($elements[$clinicId]), true);
      $this->assertNotEquals(strpos($message, $elements[$clinicId]),false);
    }
    
  }
  
  public function countElements($section, $table, $clinicId){
    $db = Yii::app()->db;
      
    if($section == "aimain")
       $sql = " SELECT count(*) as total FROM $table WHERE clinicid = ?";
    
    else if($section == "avmain")
      $sql = " SELECT count(*) as total FROM $table WHERE av_id in (SELECT av_id FROM tblavmain WHERE clinicid= ? )" ;
      
    else if($section == "test")
      $sql = " SELECT count(*) as total FROM $table WHERE testid in (SELECT testid FROM tblpatienttest WHERE clinicid= ? )" ;

    $command = $db->createCommand($sql);
    $command->bindParam(1, $clinicId, PDO::PARAM_STR);
    $row = $command->queryRow();
 
    return $row["total"] ;
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