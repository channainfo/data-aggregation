<?php
 class DaSqlHelperTest extends CTestCase {
   public function testSQLFromTableCols(){
     $table = "mytable";
     $cols = array("field1","field2","field3","field4","field5");
     
     $sql = DaSqlHelper::sqlFromTableCols($table, $cols);
     $this->assertEquals($sql, "REPLACE INTO mytable (field1,  field2,  field3,  field4,  field5) VALUES (:field1, :field2, :field3, :field4, :field5)");
   }
 }