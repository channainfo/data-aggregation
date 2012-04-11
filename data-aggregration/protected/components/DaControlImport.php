<?php
 /**
  * @property DaControl $instance
  * @property String $tableName 
  */
 class DaControlImport {
   private static $tableName;
   private static $instance;
   
   /**
    *
    * @param string $tableName
    * @return DaControl 
    */
   public static function getControlInstance($tableName){
   
     if( $tableName !="" && $tableName == self::$tableName ){
       return self::$instance;
     }
     self::$tableName = $tableName;  
     switch ($tableName){
       
       case "tblaimain" :
         self::$instance = new DaControlAiMain();
         break;
       case "tblcimain" :
         self::$instance = new DaControlCiMain();
         break;
       case "tbleimain" :
         self::$instance = new DaControlEiMain();
         break;
       
       case "tblavmain" :
         self::$instance = new DaControlAvMain();
         break;
       case "tblcvmain" :
         self::$instance = new DaControlCvMain();
         break;
       case "tblevmain" :
         self::$instance = new DaControlEvMain();
         break;
       
       
       case "tblart" :
       case "tblcart" :
         self::$instance = new DaControlART();
         break;
       case "tblavlostdead" :
         self::$instance = new DaControlAvLostDead();
         break;
       
       case "tblcvlostdead" :
         self::$instance = new DaControlCvLostDead();
         break;
       
       case "tblevlostdead" :
         self::$instance = new DaControlEvLostDead();
         break;
       
       case "tblavarv":
       case "tblcvarv" :
       case "tblcvarv" :
         self::$instance = new DaControlArv();
         break;
       
       default :
         self::$instance = null ;
         self::$tableName = null ;
     }
     return self::$instance ;
     
   }
 }