<?php
 class DaRecordReaderTest extends CTestCase{
   public function testQueriesChildren(){
      $query = DaRecordReader::queryChild("tableA", "table_id");
      $this->assertEquals($query, "SELECT * FROM tableA WHERE table_id = ?");
   }
   
   public function testGetReader(){
     $sql = DaRecordReader::getReader("tblavmain");
     $this->assertEquals($sql, "SELECT * FROM tblavmain WHERE ClinicId = ?");
   }
   
   public function testGetReaderPatient(){
     $sql = DaRecordReader::getReader("tblpatienttest");
     $this->assertEquals($sql, "SELECT * FROM tblpatienttest WHERE ClinicId = ?");
   }
   
 }