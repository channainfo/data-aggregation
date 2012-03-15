<?php
 class DaConfig{
   const PAGE_SIZE = 10 ;
   const IMPORT_TABLE_NAME = "da_import_tables";
   const IMPORT_ESC_TABLE_NAME = "da_import_escs";
   const TBL_CLINIC = "tblclinic" ;
   
   const IMPORT_TABLE_TYPE_FIXED = "fixed";
   const IMPORT_TABLE_TYPE_IMPORT = "import" ;
   
   public static function pathDataStore(){
     return dirname(__FILE__)."/../../data/";
   }
   
   public static function pathDataDb(){
     return "data/";
   }
     
   public static function generateFile($filename){
     return  time()."-".rand(0,10000)."-".$filename;
   }
   
   public static function webRoot(){
     return dirname(__FILE__)."/../../";
   }  
 }
?>
