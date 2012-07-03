<?php
 class ExportHistoryTest extends CDbTestCase {
   
   public function setUp() {
     ExportHistory::model()->deleteAll();
     parent::setUp();
   }


   public function testCreateSite(){
     
     $attributes = array(
            "reversable" => 1,
            "all_site" => 1,
            "site_list" => array ( "2" => "1901 - Stung Treng hospotal" , "5" => "1209 - Clinic Chhouk Sar" ),
            "all_table" => 0 ,
            "table_list" => array(
                                  "tables" => array(
                                          "tblaimain" => 1 ,
                                          "tblcimain" => 1 ,
                                          "tblpatienttest" => 1 ,
                                          "tblavmain" => 1 ),

                                  "columns" => array(
                                          "tblaimain" => array (
                                                                "clinicid" => 1 ,
                                                                "grou" => 1 ,
                                                                "namecontps1" => 1 ,
                                                                "maritalstatus" => 1 ,
                                                                "namelocationhbc" => 1),

                                          "tblcimain" => array (
                                                                "clinicid" => 1 ,
                                                                "datevisit" => 1 ,
                                                                "dob" => 1 ,
                                                                "sex" => 1 ,
                                                                "addguardian" => 1 ,
                                                                "house" => 1 ),

                                          "tblpatienttest" => array(
                                                                "testid" => 1 ,
                                                                "clinicid" => 1 ,
                                                                "dat" => 1 ,
                                                                "cd4" => 1 ,
                                                                "cd" => 1 ,
                                                                "cd8" => 1 ),

                                          "tblavmain" => array (
                                                                "clinicid" => 1 ,
                                                                "resp" => 1 ,
                                                                "tbinfection" => 1 ,
                                                                "familynoyes" => 1 ,
                                                                "missarv" => 1 )
                                      )));
     
     $count = ExportHistory::model()->count();
     $exportHistory = new ExportHistory();
     $exportHistory->setData($attributes);
     $success = $exportHistory->save();
     $total = ExportHistory::model()->count();
     
     $this->assertEquals($success, true);
     $this->assertEquals($count+1, $total);
     
     $sites = $exportHistory->getSites();
     $this->assertEquals(count($sites), 2);
     $this->assertEquals($sites["2"], "1901 - Stung Treng hospotal");
     $this->assertEquals($sites["5"], "1209 - Clinic Chhouk Sar");
     
     $tableList = $exportHistory->getTableList();
     $this->assertEquals(count($tableList), 4);
     
     
   }
   
   public function testSaveAsSeparate(){
     $attributes = array(
            "reversable" => 1,
            "all_site" => 1,
            "site_list" => array ( "2" => "1901 - Stung Treng hospotal" , "5" => "1209 - Clinic Chhouk Sar", "1" => "1401 - Kampong Cham"),
            "all_table" => 1 ,
            "separate"  => 1,
            "table_list" => array(
                                  "tables" => array(
                                          "tblaimain" => 1 ,
                                          "tblcimain" => 1 ,
                                          "tblpatienttest" => 1 ,
                                          "tblavmain" => 1 ),

                                  "columns" => array(
                                          "tblaimain" => array (
                                                                "clinicid" => 1 ,
                                                                "grou" => 1 ,
                                                                "namecontps1" => 1 ,
                                                                "maritalstatus" => 1 ,
                                                                "namelocationhbc" => 1),

                                          "tblcimain" => array (
                                                                "clinicid" => 1 ,
                                                                "datevisit" => 1 ,
                                                                "dob" => 1 ,
                                                                "sex" => 1 ,
                                                                "addguardian" => 1 ,
                                                                "house" => 1 ),

                                          "tblpatienttest" => array(
                                                                "testid" => 1 ,
                                                                "clinicid" => 1 ,
                                                                "dat" => 1 ,
                                                                "cd4" => 1 ,
                                                                "cd" => 1 ,
                                                                "cd8" => 1 ),

                                          "tblavmain" => array (
                                                                "clinicid" => 1 ,
                                                                "resp" => 1 ,
                                                                "tbinfection" => 1 ,
                                                                "familynoyes" => 1 ,
                                                                "missarv" => 1 )
                                      )));
     
     
     $firstCount = ExportHistory::model()->count();
     
     $exportHistory = new ExportHistory();
     $exportHistory->saveAsSeparate($attributes);
     
     $lastCount = ExportHistory::model()->count();
     
     $this->assertNotEquals($firstCount, $lastCount);
     $this->assertEquals($lastCount, 3);
     
     $records = ExportHistory::model()->findAll();
     
     $this->assertEquals($records[0]->all_site, 0);
     $this->assertEquals($records[1]->all_site, 0);
     $this->assertEquals($records[2]->all_site, 0);
     
     $site0 = $records[0]->getSites();
     $site1 = $records[1]->getSites();
     $site2 = $records[2]->getSites();

     $this->assertEquals($site0, array(2 => "1901 - Stung Treng hospotal"));
     $this->assertEquals($site1, array(5 => "1209 - Clinic Chhouk Sar"));
     $this->assertEquals($site2, array(1=>  "1401 - Kampong Cham"));
     
     $tableList0 = $records[0]->getTableList();
     $this->assertEquals(count($tableList0), 4);
     
     $tableList1 = $records[1]->getTableList();
     $this->assertEquals(count($tableList1), 4);
     
     $tableList2 = $records[2]->getTableList();
     $this->assertEquals(count($tableList2), 4);
     
     
     
     
     
   }
   
   
  
  
 }