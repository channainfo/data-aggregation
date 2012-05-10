<?php
 class DaConfig{
   const PAGE_SIZE = 15 ;
   const IMPORT_TABLE_NAME = "da_import_tables";
   const IMPORT_ESC_TABLE_NAME = "da_import_escs";
   const TBL_CLINIC = "tblclinic" ;
   
   const PASS_KEY = "NCHADS_DA" ;
   const META_EXPORT_FN  = "config.ini" ;
   
   const IMPORT_TABLE_TYPE_FIXED = "fixed";
   const IMPORT_TABLE_TYPE_IMPORT = "import" ;
   private static $importConfig = false;
   private static $importSetting = false;
   public static $env = "production";
   /**
    *
    * @return string value in web root dir -> webroot/data/ 
    */
   public static function pathDataStore(){
     return dirname(__FILE__)."/../../data/";
   }
   
   public static function mkDir($dir){
     if(!file_exists($dir))
        mkdir($dir, 0777, true);
   }
   
   public static function pathDataStoreExport(){
     return self::pathDataStore()."export/";
   }
   
   public static function importConfig(){
      if(self::$importConfig)
        return self::$importConfig ;
      
      $configFile = dirname(__FILE__)."/../config/importConfig.php" ;
      if(file_exists($configFile))
        self::$importConfig = require_once($configFile) ;
      else
        throw new DaInvalidFileException(" Could not find include file : {$configFile}");
      return self::$importConfig ;  
   }
   
   public static function importSetting($cache = true){
      if(self::$importSetting && $cache)
        return self::$importSetting ;
      
      $configFile = dirname(__FILE__)."/../config/setting.php" ;
      if(file_exists($configFile))
        self::$importSetting = require_once($configFile) ;
      else
        throw new DaInvalidFileException(" Could not find include file : {$configFile}");
      return self::$importSetting ;  
   }

   public static function pathDataDb(){
     return "data/";
   }
     
   public static function generateFile($filename){
     return  time()."-".$filename;
   }
   
   public static function webRoot(){
     return dirname(__FILE__)."/../../";
   }  
 }
?>
