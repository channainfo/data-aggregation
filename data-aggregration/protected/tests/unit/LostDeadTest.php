<?php
/**
 * @property DaFixtureTestCase $daFixture 
 */
require_once dirname(__FILE__)."/DaFixtureTestCase.php";

class LostDeadTest extends CDbTestCase {
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
         array(1,'dead','2008-10-11',1, 1),
         array(1,'lost','2008-10-12',1, 1),
         
         // ok
         array(2,'lost','2008-10-11',2, 1),
         array(2,'lost','2008-10-12',2, 1),
         array(2,'dead','2008-10-13',2, 1),
         
         
         // error
         array(3,'dead','2008-10-11',3, 1),
         array(3,'lost','2008-10-12',3, 1),
         array(3,'dead','2008-10-13',3, 1),
         
         // ok
         array(4,'lost','2008-10-11',4, 1),
         array(4,'lost','2008-10-11',4, 1),
         array(4,'lost','2008-10-11',4, 1),
         
         // error
         array(5,'dead','2008-10-11',5, 1),
         array(5,'lost','2008-10-12',5, 1),
         array(5,'lost','2008-10-13',5, 1),
         array(5,'dead','2008-10-14',5, 1),
         
         // error
         array(5,'dead','2008-10-11',6, 1),
         array(5,'lost','2008-10-12',6, 1),
         array(5,'lost','2008-10-13',6, 1),
         array(5,'dead','2008-10-14',6, 1),
         array(5,'lost','2008-10-15',6, 1),
         
         // ok
         array(6,'dead','2008-10-11',5, 1),
         array(6,'dead','2008-10-12',5, 1),
         array(6,'dead','2008-10-13',5, 1),
         
     );
   }
   
   public function testLostDead(){
     $sqlDeadFirst = " ( SELECT *  FROM {$this->daFixture->getTable()} as L1 WHERE L1.status = 'dead' ) ";
     $sqlLostLast  = " ( SELECT *  FROM {$this->daFixture->getTable()} as L2 WHERE L2.status = 'lost' ) ";
          
     $sql = " SELECT Lost.*, Lost.lddate as LostDate, Dead.lddate as DeadDate  FROM "
          . "\n {$sqlLostLast} AS Lost , {$sqlDeadFirst} AS Dead "
          . "\n WHERE Lost.lddate > Dead.lddate "
          . "\n AND Lost.ClinicID = Dead.ClinicID  "
          . "\n AND Lost.av_id = Dead.av_id  "
          . "\n GROUP BY Lost.ClinicID, Lost.av_id "   
          ;
         
     $errorRecords = Yii::app()->db->createCommand($sql)->queryAll();
     
     $this->assertEquals(count($errorRecords), 4 );
     $fixturesData = $this->daFixture->getFixtures(); 
     
     
     $this->assertEquals($errorRecords[0]["ClinicID"], 1 );
     $this->assertEquals($errorRecords[1]["ClinicID"], 3 );
     $this->assertEquals($errorRecords[2]["ClinicID"], 5 );
     $this->assertEquals($errorRecords[3]["ClinicID"], 5 );
     
   }
 }
