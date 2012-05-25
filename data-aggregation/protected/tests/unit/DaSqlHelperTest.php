<?php
 class DaSqlHelperTest extends CTestCase {
   public function testSQLFromTableColsWthReplace(){
     $table = "mytable";
     $cols = array("field1","field2","field3","field4","field5");
     
     $sql = DaSqlHelper::sqlFromTableCols($table, $cols);
     $this->assertEquals($sql, "REPLACE INTO mytable (field1, field2, field3, field4, field5) VALUES (:field1, :field2, :field3, :field4, :field5)");
   }
   
   public function testSQLFromTableColsWithSkips(){
     $table = "mytable";
     $cols = array("field1","field2","field3","field4","field5", false);
     $skips = array("field1") ;
     
     $sql = DaSqlHelper::sqlFromTableCols($table, $cols, false,$skips);
     $this->assertEquals($sql, "INSERT INTO mytable (field2, field3, field4, field5) VALUES (:field2, :field3, :field4, :field5)");

   }
   
   public function testInsertTableColsWithIndexParam(){
     $sql = DaSqlHelper::insertTableColsWithIndexParam("mytable", array("field1", "field2", "field3"));
     $this->assertEquals($sql, "REPLACE INTO mytable (field1,field2,field3) VALUES (?,?,?)");
     
     $sql = DaSqlHelper::insertTableColsWithIndexParam("mytable", array("field1", "field2", "field3"), false);
     $this->assertEquals($sql, "INSERT INTO mytable (field1,field2,field3) VALUES (?,?,?)");
   }
   
   public function testAdd(){
     $sql = DaSqlHelper::insertTableWithIndexParam("tblclinic");
     $command = Yii::app()->db->createCommand($sql);
     
     $record = array("1","2","3","4","5","6");
     $n = count($record);
     for($i=0;$i<$n;$i++){
       $command->bindParam($i+1, $record[$i], PDO::PARAM_STR);
     }
     $command->execute();
   }
   
   public function testNumberLeftColumn(){
     $samples = array( array( "fields" => array(
                                      'clinicid','datevisit','dob','sex','addguardian','house','street','grou',
                                      'village','commune','district','province','phone','namecontps1','contaddress1',
                                      'contphone1','namecontps2','contaddress2','contphone2','childstatus','fatherstatus',
                                      'motherstatus','education','refer','hbcteam','ftesdate','fage','foption','fresult',
                                      'stestdate','sage','soption','sresult','offyesno','officein','datearv','arvnumber',
                                      'tbpastmedical','vaccinat','infantnutrit','previousarv','precontrimoxazole',
                                      'prefluconzazole','pretranditional','drugallergy'),
                              "value" => 1, 
                              "sitecode" => "0001",
                              "table" => "tblcimain" ),
                              
                       array( 
                              "fields" => array(
                                      'clinicid','datevisit','dob','sex','addguardian','house','street','grou',
                                      'village','commune','district','province','phone','namecontps1','contaddress1',
                                      'contphone1','namecontps2','contaddress2','contphone2','childstatus','fatherstatus',
                                      'motherstatus','education','refer','hbcteam','ftesdate','fage','foption','fresult',
                                      'stestdate','sage','soption','sresult','offyesno','officein','datearv','arvnumber',
                                      'tbpastmedical','vaccinat','infantnutrit','previousarv','precontrimoxazole',
                                      'prefluconzazole','pretranditional','drugallergy', "eclinicid"),
                              "value" => 0, 
                              "sitecode" => "0001",
                              "table" => "tblcimain" ),
         
                       array( 
                           "fields" => array( 'idcommune','iddistrict','communeen' ),
                           "value" => 0,
                           "sitecode" => false,
                           "table" => "tblcommune" 
                           ),
                        array( 
                           "fields" => array( 'idcommune','iddistrict' ),
                           "value" => 1,
                           "sitecode" => false,
                           "table" => "tblcommune" 
                           ),   
         
         );
     
     foreach($samples as $sample){
      $left = DaSqlHelper::numberLeftColumn($sample["fields"], $sample["table"], $sample["sitecode"]);
      $this->assertEquals($left, $sample["value"]);
     }
   }
 }