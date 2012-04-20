<?php
 class DaImportSequenceTest extends CDbTestCase {
   
   public $site;
   public $import ;
   
   public function __construct() {
     $this->cleanUp();
   }
   
   public function setUp() {
     parent::setUp();
     
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
   
   public function testImportEIMain(){
     $import = new DaImportSequence(Yii::app()->db, $this->site->code );
     $import->importIMain("tbleimain");
     
     $rejectPatients = $import->rejectPatients();
     $patients = $this->getInsertedPatients("tbleimain", $this->site->code);
     
     $this->assertEquals(count($patients), 3);
     $this->assertEquals($patients[0]["ClinicID"], "123");
     $this->assertEquals($patients[1]["ClinicID"], "1234");
     $this->assertEquals($patients[2]["ClinicID"], "1234567890");
     
     $this->assertEquals(count($rejectPatients), 6);
     
     $patient1 = $this->unserializePatient($rejectPatients[0]);
     $this->assertEquals( preg_match("/DateVisit/i", $patient1["message"][0])> 0 , true );
     $this->assertEquals( $this->strEqual($patient1["err_records"]["tbleimain"][0]["ClinicID"] , "12"), true );
     $this->assertEquals( $this->strEqual($patient1["record"]["ClinicID"], "12" ), true);
     
     $patient2 = $this->unserializePatient($rejectPatients[1]);
     $this->assertEquals( preg_match("/DateVisit/i", $patient2["message"][0])> 0 , true );
     $this->assertEquals( $this->strEqual($patient2["err_records"]["tbleimain"][0]["ClinicID"] , "12345"), true );
     $this->assertEquals( $this->strEqual($patient2["record"]["ClinicID"], "12345" ), true);
     
     $patient3 = $this->unserializePatient($rejectPatients[2]);
     $this->assertEquals( preg_match("/Status/i", $patient3["message"][0])> 0 , true );
     $this->assertEquals( $this->strEqual($patient3["err_records"]["tblevlostdead"][0]["ClinicID"] , "123456"), true );
     $this->assertEquals( $this->strEqual($patient3["record"]["ClinicID"], "123456" ), true);
     

     $patient4 = $this->unserializePatient($rejectPatients[3]);
     $this->assertEquals( preg_match("/\[ARV\]/i", $patient4["message"][0])> 0 , true );
     $this->assertEquals( $this->strEqual($patient4["err_records"]["tblevarv"][0]["ARV"] , "ppp"), true );
     $this->assertEquals( $this->strEqual($patient4["record"]["ClinicID"], "1234567" ), true);
     
     $patient5 = $this->unserializePatient($rejectPatients[4]);
     $this->assertEquals( preg_match("/Status/i", $patient5["message"][0])> 0 , true );
     $this->assertEquals( $this->strEqual($patient5["err_records"]["tblevlostdead"][0]["ClinicID"] , "12345678"), true );
     $this->assertEquals( $this->strEqual($patient5["record"]["ClinicID"], "12345678" ), true);
     
     
     $patient6 = $this->unserializePatient($rejectPatients[5]);
     $this->assertEquals( preg_match("/\[ARV\]/i", $patient6["message"][0])> 0 , true );
     $this->assertEquals( $this->strEqual($patient6["err_records"]["tblevarv"][0]["ARV"] , "Jjjj"), true );
     $this->assertEquals( $this->strEqual($patient6["record"]["ClinicID"], "123456789" ), true);
     
     $visits = $this->getVisitMain("tblevmain", $this->site->code, "123");
     $this->assertEquals( count($visits),2);
     $this->assertEquals( $this->strEqual($visits[0]["ClinicID"],"123"), true);
     $this->assertEquals( $this->strEqual($visits[1]["ClinicID"],"123"), true);
     
     $visits = $this->getVisitMain("tblevmain", $this->site->code, "1234567890");
     $this->assertEquals( count($visits),1);
     $this->assertEquals( $this->strEqual($visits[0]["ClinicID"],"1234567890"), true);
     $this->assertEquals( $this->strEqual($visits[0]["EID"],"14"), true);
     
     
     $lostdeads = $this->getEvLostDead($this->site->code, "1234567890");
     $this->assertEquals(count($lostdeads), 2);
     $this->assertEquals($this->strEqual($lostdeads[0]["ClinicID"], "1234567890"), true);
     $this->assertEquals($this->strEqual($lostdeads[0]["EID"], "14"), true);
     $this->assertEquals($this->strEqual($lostdeads[0]["Status"], "dead"), true);
     
     $this->assertEquals($this->strEqual($lostdeads[1]["ClinicID"], "1234567890"), true);
     $this->assertEquals($this->strEqual($lostdeads[1]["EID"], "14"), true);
     $this->assertEquals($this->strEqual($lostdeads[1]["Status"], "lost"), true);
     
     $arvs = $this->getEvARV($this->site->code, 14);
     $this->assertEquals(count($arvs), 1);
     $this->assertEquals($arvs[0]["ARV"], "ddI");
     
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
 }