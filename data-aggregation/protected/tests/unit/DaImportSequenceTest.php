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
     
     $this->truncateRejectPatients();
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
    $import = new DaImportSequence(Yii::app()->db, $this->site->code);
    $import->importIMain("tblaimain");
            
    $patients = $this->getInsertedPatients("tblaimain", $this->site->code);
    $this->assertEquals(count($patients), 8);
    
    
    $rejectPatients = $import->rejectPatients("tblaimain", 0 , 100);
    $this->assertEquals(count($rejectPatients), 14);
    
    
    $total = $import->getTotalPatientIter();
    $warning = 1 ;
    $this->assertEquals(count($patients)+count($rejectPatients), $total + $warning );
    
    $clinics = array("000004" ,"000005", "000006", "000007", "000008", "000010", "000016", "000017");
    
    foreach($patients as $index => $patient){
      //echo "\n result : {$patient["CLinicID"]}-expected: {$clinics[$index]}";
      $found = array_search(trim($patient["CLinicID"]), $clinics);
      $exist = $found !== false ;
      $this->assertTrue($exist);
    }
    
    $elements = array(
              "avmain" => array(        
                      "avlostdead" => array(0,1,0,0,2,2,0,2),
                      "avarv" => array(3,10,6,6,6,1,1,3),
                      "avtb" => array(0,2,3,1,0,0,0,0),
                      "appoint" => array(0,3,1,1,2,0,0,0),
                      "avoidrugs" => array(5,0,0,4,2,0,0,0),
                      "avtbdrugs" => array(1,2,1,0,0,0,3,0),
                      ),
            
               "aimain" => array(   
                      "avmain" => array(3,5,3,4,2,1,1,1),       
                      "aitraditional" => array(1,1,1,1,1,1,0,0),
                      "art" => array(1,0,1,1,1,1,1,1),
                      "aiarvtreatment" => array(1,1,1,1,1,0,1,0),
                      "aidrugallergy" => array(0,1,1,1,0,1,1,0),
                      "aicotrimo" => array(1,0,1,1,0,1,1,0),
                      "aiothpasmedical" => array(1,1,1,1,1,0,0,0),
                      "aifamily" => array(3,3,4,6,1,3,0,0),
                      "aitbpastmedical" => array(1,1,1,1,1,1,1,0),
                      "aifluconazole" => array(0,0,1,1,1,1,1,0),
                      "aiothermedical" => array(0,1,0,0,2,0,0,0),
                      "aiisoniazid" => array(1,1,0,0,0,0,0,0),
                      "patienttest" => array(0,6,12,3,4,2,5,15),
                     ),
            
               "test" => array(       
                      "testcxr" => array(0,2,3,0,0,0,3,0),
                      "testabdominal" => array(0,1,2,4,0,2,0,0)
                      )
    );
    
    foreach($clinics as $clinicIndex => $clinicId){
       foreach($elements as $section => $tableList){
          foreach($tableList as $tableName => $tableValue){
              $tblName = "tbl".$tableName ;
              $expected = $tableValue[$clinicIndex];
              $result = $this->countElements($section, $tblName, $clinicId);
              //echo "\n - clinic:{$clinicId}, table:{$tblName}".($result == $expected) ."- *** result:{$result}-expected:{$expected}";
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
                        "000017"=> "Invalid tblavlostdead. [Date] = '2010-09-01 12:00:00.000'",
                        "000018"=> "Invalid [LDDate] = 1900-07-09 00:00:00.000 in [tblavlostdead]" ,
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
      $exist = strpos($message, $elements[$clinicId]) !== false ;
      $this->assertTrue( $exist);
    }
  }
  
  public function testImportCiMain(){
    
    $import = new DaImportSequence(Yii::app()->db, $this->site->code);
    $import->importIMain("tblcimain");
            
    $patients = $this->getInsertedPatients("tblcimain", $this->site->code);
    $this->assertEquals(count($patients), 3);
    
    
    $rejectPatients = $import->rejectPatients("tblcimain", 0 , 100);
    $this->assertEquals(count($rejectPatients), 10);
    
    $total = $import->getTotalPatientIter();
    $this->assertEquals(count($patients)+count($rejectPatients), $total);
    
    $clinics = array("P000004", "P000006", "P000010" );
    
    foreach($patients as $index => $patient){
      //echo "\n result : {$patient["CLinicID"]}-expected: {$clinics[$index]}";
      //print_r($patient);
      //$found = array_search(trim($patient["CLinicID"]), $clinics);
      //$this->assertNotEquals($found, false);
    }
    
    $elements = array(
              "cvmain" => array(        
                      "cvlostdead" => array(1,2,1),
                      "cvarv" => array(4,13,11),
                      "cvtb" => array(6,8,4),
                      "cvoi" => array(8,6,6),
                      "cvarvoi" => array(11,10,7),
                      ),
            
               "cimain" => array(   
                      "cvmain" => array(5,4,4),       
                      "citraditional" => array(1,1,0),
                      "cart" => array(1,1,1),
                      "ciarvtreatment" => array(2,1,3),
                      "cidrugallergy" => array(3,2,1),
                      "cicotrimo" => array(1,1,0),
                      "ciothpastmedical" => array(4,4,1),
                      "cifamily" => array(3,3,3),
                      "citbpastmedical" => array(3,1,2),
                      "cifluconazole" => array(1,0,1),
                      "patienttest" => array(4,2,3),
                     ),
            
               "test" => array(       
                      "testcxr" => array(5,5,9),
                      "testabdominal" => array(10,5,6)
                      )
    );
    
    foreach($clinics as $clinicIndex => $clinicId){
       foreach($elements as $section => $tableList){
          foreach($tableList as $tableName => $tableValue){
              $tblName = "tbl".$tableName ;
              $expected = $tableValue[$clinicIndex];
              $result = $this->countElements($section, $tblName, $clinicId);
              //echo "\n *" .($result === $expected) ." ** result:{$result}-expected:{$expected}, table:{$tblName}, clinic:{$clinicId}";
              $this->assertEquals($result, $expected);
          }
       }
    }
    
    $elements = array(  "P000001" => "Invalid [OfficeIn]. [OfficeIn]" ,
                        "P000002" => "Invalid [DateVisit]: 1900-03-19 00:00:00.000" ,
                        "P000003" => "[tblcimain] invalid sex. Gay" ,
                        "P000016" => "[tblcvmain] ARVNumber: P190100016 does not exist in tblcvmain with ClinicId: P000016" ,
                        "P000017" => "[tblcart] ARVNumber: P190100017 does not exist in tblcart with ClinicId: P000017" ,
                        "P000018" => "Invalid [ARVNumber] :123456" ,
                        "P000005" => "Invalid [OfficeIn]. [OfficeIn] should not be empty when [OffYesNo]= Yes" ,
                        "P000007" => "[tblcart] ARVNumber: P190100007 does not exist in tblcart with ClinicId: P000007" ,
                        "P000008" => "[ARTNum] P030000008 does not exist in tblcart" ,
                        "P000009" => "Invalid [ARV] . [ARV] = ['kkkk'] is not in '( 3TC,ABC,AZT,d4T," ,
    );
    
    foreach($rejectPatients as $index => $rejectPatient){
      $p = $this->unserializePatient($rejectPatient);
      $record = $p["record"];
      //print_r($record);
      $message = $p["message"][0];
      $clinicId = trim($record["ClinicID"]);
      //echo "\n result clinicid:{$record["ClinicID"]}-expected:$elements[$clinicId]";
      $this->assertEquals(isset($elements[$clinicId]), true);
      $exist = strpos($message, $elements[$clinicId]) !== false ;
      $this->assertTrue($exist);
    }
    
  }
  
  
  /**
   *
   * @param type $section
   * @param type $table
   * @param type $clinicId
   * @return type 
   */
  public function countElements($section, $table, $clinicId){
    $db = Yii::app()->db;
      
    if($section == "aimain" || $section == "cimain" || $section == "eimain" )
       $sql = " SELECT count(*) as total FROM $table WHERE clinicid = ?";
    
    else if($section == "avmain")
      $sql = " SELECT count(*) as total FROM $table WHERE av_id in (SELECT av_id FROM tblavmain WHERE clinicid= ? )" ;
    else if($section == "cvmain")
      $sql = " SELECT count(*) as total FROM $table WHERE cid in (SELECT cid FROM tblcvmain WHERE clinicid= ? )" ;
    else if($section == "evmain"){
      $sql = " SELECT count(*) as total FROM $table WHERE eid in (SELECT eid FROM tblevmain WHERE clinicid= ? )" ;
    }
      
    else if($section == "test")
      $sql = " SELECT count(*) as total FROM $table WHERE testid in (SELECT testid FROM tblpatienttest WHERE clinicid= ? )" ;
    
    $command = $db->createCommand($sql);
    $command->bindParam(1, $clinicId, PDO::PARAM_STR);
    $row = $command->queryRow();
    return $row["total"] ;
  }
  
  public function testImportEIMain(){
     $import = new DaImportSequence(Yii::app()->db, $this->site->code );
     $import->importIMain("tbleimain");
     
     $rejectPatients = $import->rejectPatients("tbleimain");
     $patients = $this->getInsertedPatients("tbleimain", $this->site->code);
     $clinics = array("123", "1234","12345678", "1234567890");
     
     foreach($patients as $index => $patient){
       //echo "\n result: {$patient["ClinicID"]}- expected: $clinics[$index] " ;
       $this->assertEquals(trim($patient["ClinicID"]), $clinics[$index]);
     }
     
     $elements = array(
              "evmain" => array(        
                      "evlostdead" => array(2,0,1,2),
                      "evarv" => array(3,0,2,2)
                      ),
            
               "eimain" => array(   
                      "evmain" => array(2,0,2,1),       
                      "patienttest" => array(1,1,0,1),
                     ),
            
               "test" => array(       
                      "testcxr" => array(2,1,0,4),
                      "testabdominal" => array(1,2,0,2)
                      )
    );
    
    foreach($clinics as $clinicIndex => $clinicId){
       foreach($elements as $section => $tableList){
          foreach($tableList as $tableName => $tableValue){
              $tblName = "tbl".$tableName ;
              $expected = $tableValue[$clinicIndex];
              $result = $this->countElements($section, $tblName, $clinicId);
              //echo "\n clinic:{$clinicId} *" .($result === $expected) ." ** result:{$result}-expected:{$expected}, table:{$tblName}";
              $this->assertEquals($result, $expected);
          }
       }
    }
    
     
     
     
     $elements = array( "12"        => '[DateVisit] invalid: "1900-01-01 00:00:00.000"' ,
                        "12345"     => '[DateVisit] invalid: "1900-01-01 00:00:00.000"' ,
                        "123456"    => '[DateVisit] invalid: ""' ,
                        "1234567"   => "Invalid [ARV] . [ARV] = ['ppp'] is not in '( 3TC,ABC,AZT,d4T,ddI,EFV,IDV" ,
                        "12345678"  => "Invalid tblevlostdead. [Date] =" ,
                        "123456789" => "Patient is not under 2 years old" ,
     );
     
     foreach($rejectPatients as $rejectPatient){
      $p = $this->unserializePatient($rejectPatient);
      $clinicid = trim($p["record"]["ClinicID"]); 
      $message = $p["message"][0];
      $exist = strpos($message, $elements[$clinicid]) !== false;
      //echo "\n\n clinic: {$clinicid} - result  :$message  ";
      //echo "\n expected:{$elements[$clinicid]}" ;
      $this->assertTrue($exist);
     }
   }
   
   private function unserializePatient($patient){
      return array( "message" => unserialize($patient["message"]),
              "err_records" => unserialize($patient["err_records"]),
              "record" => unserialize($patient["record"])
           );
     
   }
   
   private function getInsertedPatients($table, $code){
     $sql = "SELECT * FROM  {$table} WHERE ID = ? " ;
     $command = Yii::app()->db->createCommand($sql);
     $command->bindParam(1, $code , PDO::PARAM_STR );
     $records = $command->queryAll();
     return $records ;
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
   
   public function truncateRejectPatients(){
     $db = Yii::app()->db ;
     $sql = "truncate da_reject_patients ";
     $command = $db->createCommand($sql);
     $command->execute();
   }
 }