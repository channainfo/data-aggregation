<?php
 class DaRecordReader {
 
   public static function ClinicChildren(){
     $childrenTables = array("tblaimain", "tblcimain");
     return array("children" => $childrenTables, "parentId" => "id");
   }
   
   public static function CiMainChildrenPartial(){
     $childrenTables = array(
         "tblcifamily","tblcidrugallergy","tblcifluconazole","tblciarvtreatment",
         "tblcitbpastmedical","tblcicotrimo","tblciothpastmedical","tblcart","tblcitraditional",
      );
     return array("children" => $childrenTables, "parentId" => "ClinicId");
   }

   public static function IMainChildrenPartial($tableName){
     if($tableName == "tblaimain")
       return self::AiMainChildrenPartial();
     else if($tableName == "tblcimain")
       return self::CiMainChildrenPartial ();
     
   }
   
   public static function CiMainChildren(){ // ClinicId
     $result = self::CiMainChildrenPartial();
     $result["children"][] = "tblpatienttest" ;
     $result["children"][] = "tblcvmain" ;
     
     return $result ;
   }
   
   public static function AiMainChildren(){ //ClinicId
     $result = self::AiMainChildrenPartial();
     $result["children"][] = "tblpatienttest" ;
     $result["children"][] = "tblavmain";  
     return $result;
   }
   
   public static function AiMainChildrenPartial(){
     $childrenTables = array(
         "tblart","tblaiothermedical","tblaiisoniazid","tblaiarvtreatment",
         "tblaidrugallergy","tblaicotrimo","tblaiothpasmedical","tblaifamily","tblaitbpastmedical",
         "tblaifluconazole","tblaitraditional"
     );
     return array("children" => $childrenTables, "parentId" => "ClinicId");
   }


   public static function CvMainChildren(){ // Cid
     $childrenTables = array("tblcvlostdead","tblcvarvoi","tblcvoi","tblcvarv","tblcvtb");
     return array("children" => $childrenTables, "parentId" => "CId");
   }
   
   public static function AvMainChildren(){ //AV_ID
     $childrenTables = array("tblavlostdead","tblavarv","tblavtbdrugs","tblappoint","tblavoidrugs","tblavtb");
     return array("children" => $childrenTables, "parentId" => "AV_ID");
   }
   
   public static function PatientTestChildren(){ //TestID
     $childrenTables = array("tbltestcxr", "tbltestabdominal");
     return array("children" => $childrenTables, "parentId" => "TestID");
   }
   
   public static function queryChild( $child, $foreignKey=null ){
      $sql =  "SELECT * FROM {$child}" ;
      if($foreignKey)
        $sql = "{$sql} WHERE {$foreignKey} = ?" ;
      return $sql;
   }
   
   public static function getChildren($parentTable){
     if($parentTable == "tblclinic")
       return self::ClinicChildren ();
     
     else if($parentTable == "tblaimain")
       return self::AiMainChildren ();
     
     else if($parentTable == "tblavmain")
       return self::AvMainChildren ();
     
     else if($parentTable == "tblpatienttest")
       return self::PatientTestChildren ();
     
     else if($parentTable == "tblcimain" )
       return self::CiMainChildren ();
     
     else if($parentTable == "tblcvmain")
       return self::CvMainChildren ();
     
   }

   public static function getReader($tableName){
     $readers = self::ClinicChildren();
     if( array_search($tableName, $readers["children"]) !==false ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
     
     $readers = self::AiMainChildren ();
     if( array_search($tableName, $readers["children"]) !==false ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
         
     $readers = self::AvMainChildren ();
     if( array_search($tableName, $readers["children"]) !==false ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
     
     $readers = self::PatientTestChildren ();
     if( array_search($tableName, $readers["children"]) !==false ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
     
     $readers = self::CiMainChildren ();
     if( array_search($tableName, $readers["children"]) !==false ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
         
     $readers = self::CvMainChildren ();
     if( array_search($tableName, $readers["children"]) !==false ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
     throw new Exception("DaRecordReader could not find reader for table:[{$tableName}] ");
   }
   
  
   
 }