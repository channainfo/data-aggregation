<?php
 class DaConfig{
   const PAGE_SIZE = 10 ;
   
   public static function pathDataStore(){
     return dirname(__FILE__)."/../../data/";
   }
   
   public static function pathDataDb(){
     return "data/";
   }
     
   public static function generateFile($filename){
     return  time()."-".rand(0,10000)."-".$filename;
   }
 }
?>
