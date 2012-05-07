<?php
 class DaRecordReader {
 
   public static function ClinicChildren(){
     $childrenTables = array("tblaimain", "tblcimain");
     return array("children" => $childrenTables, "parentId" => "id");
   }
   
   //======================xIMainChildrenPartial================================
   public static function EiMainChildrenPartial(){
     return array("children" => array(), "parentId" => "ClinicId");
   }
   
   public static function CiMainChildrenPartial(){
     $childrenTables = array(
         "tblcifamily","tblcidrugallergy","tblcifluconazole","tblciarvtreatment",
         "tblcitbpastmedical","tblcicotrimo","tblciothpastmedical","tblcart","tblcitraditional",
      );
     return array("children" => $childrenTables, "parentId" => "ClinicId");
   }
   
   public static function AiMainChildrenPartial(){
     $childrenTables = array(
         "tblart","tblaiothermedical","tblaiisoniazid","tblaiarvtreatment",
         "tblaidrugallergy","tblaicotrimo","tblaiothpasmedical","tblaifamily","tblaitbpastmedical",
         "tblaifluconazole","tblaitraditional"
     );
     return array("children" => $childrenTables, "parentId" => "ClinicId");
   }
   
   //======================MainChildrenPartial==================================
   public static function IMainChildrenPartial($tableName){
     if($tableName == "tblaimain")
       return self::AiMainChildrenPartial();
     
     else if($tableName == "tblcimain")
       return self::CiMainChildrenPartial ();
     
     else if($tableName == "tbleimain")
       return self::EiMainChildrenPartial ();
   }

   //====================xIMain=================================================
   public static function EiMainChildren(){ //ClinicId
     $result = self::EiMainChildrenPartial();
     $result["children"][] = "tblevmain" ;
     
     return $result ;
   }
   
   public static function CiMainChildren(){ //ClinicId
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
   //========= xVMain ==========================================================
   public static function EvMainChildren(){//Eid
     $childrenTables = array("tblevlostdead","tblevarv");
     return array("children" => $childrenTables, "parentId" => "EId");
   }

   public static function CvMainChildren(){ // Cid
     $childrenTables = array("tblcvlostdead","tblcvarvoi","tblcvoi","tblcvarv","tblcvtb");
     return array("children" => $childrenTables, "parentId" => "CId");
   }
   
   public static function AvMainChildren(){ //AV_ID
     $childrenTables = array("tblavlostdead","tblavarv","tblavtbdrugs","tblappoint","tblavoidrugs","tblavtb");
     return array("children" => $childrenTables, "parentId" => "AV_ID");
   }
   
   //=====================PatientTestChildren===================================
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
     
     else if($parentTable == "tblevmain")
       return self::EvMainChildren ();
   }
   
   public static function getReader($tableName){
     $readers = self::ClinicChildren();
     if( self::searchChild($tableName, $readers["children"]) !==false ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
     
     $readers = self::AiMainChildren ();
     if( self::searchChild($tableName, $readers["children"])  ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
         
     $readers = self::AvMainChildren ();
     if( self::searchChild($tableName, $readers["children"]) ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
     
     $readers = self::PatientTestChildren ();
     if( self::searchChild($tableName, $readers["children"]) ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
     
     $readers = self::CiMainChildren ();
     if( self::searchChild($tableName, $readers["children"]) ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
         
     $readers = self::CvMainChildren ();
     if( self::searchChild($tableName, $readers["children"]) ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
     
     $readers = self::EiMainChildren ();
     if( self::searchChild($tableName, $readers["children"]) ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
     
     $readers = self::EvMainChildren ();
     if( self::searchChild($tableName, $readers["children"]) ){
       return self::queryChild ( $tableName , $readers["parentId"]);
     }
     
     throw new Exception("DaRecordReader could not find reader for table:[{$tableName}] ");
   }
   
   public static function searchChild($table, $parent){
     return array_search($table, $parent) !==false ;
   }
   
   public static function getIdFromRecord($table, $record){
     $configs = DaConfig::importConfig();
     $key = $configs["keys"][$table];
     return $record[$key];
   }
  
   
 }