<?php
 class DaConfig{
   const PAGE_SIZE = 10 ;
   const IMPORT_TABLE_NAME = "da_import_tables";
   const IMPORT_ESC_TABLE_NAME = "da_import_escs";
   const TBL_CLINIC = "tblclinic" ;
   
   const IMPORT_TABLE_TYPE_FIXED = "fixed";
   const IMPORT_TABLE_TYPE_IMPORT = "import" ;
   
   const CODE_ERROR_CODE = "" ;
   
   const CTRL_EXCEPTION_AIMAIN = 100 ;
   const CTRL_EXCEPTION_CIMAIN = 99 ;
   
   const CTRL_EXCEPTION_AVMAIN = 98 ;
   const CTRL_EXCEPTION_CVMAIN = 97 ;
   
   const CTRL_EXCEPTION_ART    = 96 ;
   
   const CTRL_EXCEPTION_AVLOSTDEAD = 95 ;
   const CTRL_EXCEPTION_CVLOSTDEAD = 94 ;
   
   const CTRL_EXCEPTION_AVARV = 93 ;
   const CTRL_EXCEPTION_CVARV = 92 ;
   
   
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
