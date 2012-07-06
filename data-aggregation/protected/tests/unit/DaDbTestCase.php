<?php
 /**
  * @property CDbConnection $dbx; 
  */

 class DaDbTestCase extends CDbTestCase {
   public $dbx ;
   public function setUp() {
     $this->dbx = DaDbMsSqlConnect::connect("localhost", "site2", "sa", "123456");
     parent::setUp();
   }
 }