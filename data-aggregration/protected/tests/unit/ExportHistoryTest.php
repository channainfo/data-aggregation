<?php
 class ExportHistoryTest extends CDbTestCase {
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
   }
   
  
 }