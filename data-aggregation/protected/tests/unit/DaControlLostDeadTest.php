<?php
/**
 * @property DaFixtureTestCase $daFixture 
 */
require_once dirname(__FILE__)."/DaFixtureTestCase.php";

class DaControlLostDeadTest extends CDbTestCase {
   /**
    *
    * @var DaFixtureTestCase 
    */
   public $daFixture = null ;

   public function setUp() {
      $table = "tblavlostdead";
      $config = DaConfig::importConfig();
      $cols = $config["tables"][$table];
      $this->daFixture = new DaFixtureTestCase($table, $cols, $this->_fixtureDatas());
   }
   private function _fixtureDatas(){
     return array(
         // error
         array(1,'lost','2008-10-10',1, 1),
         array(1,'dead','2008-10-11',1, 2),
         array(1,'lost','2008-10-12',1, 3),
         
         // ok
         array(2,'lost','2008-10-11',2, 4),
         array(2,'lost','2008-10-12',2, 5),
         array(2,'dead','2008-10-13',2, 6),
         
         
         // error
         array(3,'dead','2008-10-11',3, 7),
         array(3,'lost','2008-10-12',3, 7),
         array(3,'dead','2008-10-13',3, 8),
         
         // ok
         array(4,'lost','2008-10-11',4, 9),
         array(4,'lost','2008-10-11',4, 9),
         array(4,'lost','2008-10-11',4, 10),
         
         // error
         array(5,'dead','2008-10-11',5, 11),
         array(5,'lost','2008-10-12',5, 11),
         array(5,'lost','2008-10-13',5, 12),
         array(5,'dead','2008-10-14',5, 13),
         
         // error
         array(5,'dead','2008-10-11',6, 14),
         array(5,'lost','2008-10-12',6, 15),
         array(5,'lost','2008-10-13',6, 16),
         array(5,'dead','2008-10-14',6, 17),
         array(5,'lost','2008-10-15',6, 18),
         
         // ok
         array(6,'dead','2008-10-11',5, 19),
         array(6,'dead','2008-10-12',5, 20),
         array(6,'dead','2008-10-13',5, 21),
         
     );
   }
   
   private function getDbx(){
     $attributes = array("host" => "localhost", "db" => "site2", "user" => "sa", "password" => "123456");
     $dbX = DaDbMsSqlConnect::connect($attributes["host"], $attributes["db"],
              $attributes["user"], $attributes["password"]);
     return $dbX ;
   }
   public function testLoadErrorLostDead(){
     $dbX = $this->getDbx();
     $evLostDead = new DaControlEvLostDead();
     $clinicids = array( array("123456", true),  
                         array("1234567", false),  
                         array("12345678", true) , 
                         array("123456789", false),
                         array("1234567890", false)
     );

     foreach($clinicids as  $clinicid ){
       $hasError = $evLostDead->loadErrorLostDead($dbX, $clinicid[0]);
       //echo "\n result:{$hasError}-expected:{$clinicid[1]} ";
       $this->assertEquals($hasError, $clinicid[1]);
     }
   }
   public function testLoadErrorsLostDead(){
      $dbX = $this->getDbx();
      $evLostDead = new DaControlEvLostDead();
      $clinicids = array( array("123456", 2), array("123456", 2) , 
                         array("1234567", 2), array("1234567", 2), 
                         array("1234567890", 2), array("1234567890", 2),
     );
      foreach($clinicids as  $clinicid ){
       $records = $evLostDead->loadRecords($dbX, $clinicid[0]);
       $this->assertEquals(count($records), $clinicid[1]);
     }
   }
   
   public function testQueryLostDead(){
     $sqlDeadFirst = " ( SELECT *  FROM {$this->daFixture->getTable()} as L1 WHERE L1.status = 'dead' ) ";
     $sqlLostLast  = " ( SELECT *  FROM {$this->daFixture->getTable()} as L2 WHERE L2.status = 'lost' ) ";
          
     $sql = " SELECT Lost.ClinicID, Lost.av_id  FROM "
          . "\n {$sqlLostLast} AS Lost , {$sqlDeadFirst} AS Dead "
          . "\n WHERE Lost.lddate > Dead.lddate "
          . "\n AND Lost.ClinicID = Dead.ClinicID  "
          . "\n AND Lost.av_id = Dead.av_id  "
          . "\n GROUP BY Lost.ClinicID, Lost.av_id "   
          ;
         
     //echo "\n {$sql}";
     
     $errorRecords = Yii::app()->db->createCommand($sql)->queryAll();
     
     $this->assertEquals(count($errorRecords), 4 );
     
     $this->assertEquals($errorRecords[0]["ClinicID"], 1 );
     $this->assertEquals($errorRecords[1]["ClinicID"], 3 );
     $this->assertEquals($errorRecords[2]["ClinicID"], 5 );
     $this->assertEquals($errorRecords[3]["ClinicID"], 5 );
     
   }
 }
