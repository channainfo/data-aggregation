<?php
 class DaImportSiteJob {
   public $code = null ;
   public $siteconfig = null;
   
   public function __construct($sitecode) {
     $this->code = $sitecode ;
     $this->siteconfig = SiteConfig::model()->findByAttributes(array("code" => $this->code));
   }
   
   public function perform(){
     try{
      $importer  = new DaImportSequence(Yii::app()->db, $this->code);
      $importer->start();
     }
     catch(DaInvalidSiteException $ex){
       $msg =  "Failed:  invalid site with message : ". $ex->getMessage()." at line : ". $ex->getLine()." at file: ".$ex->getFile();
       $this->log($msg);
     }
     catch(DaInvalidSiteDatabaseException $ex){
       $msg =  "Failed: connection with message : ". $ex->getMessage()." at line : ". $ex->getLine()." at file: ".$ex->getFile();
       $this->log($msg);
     }
     catch(Exception $ex){
       $msg =  "Failed: with message : ". $ex->getMessage()." at line : ". $ex->getLine()." at file: ".$ex->getFile();
       $this->log($msg);
     }
   }
   
   public function log($msg){
     $text = date("Y-m-d, H:i:s")."::".$msg."\n\n";
     echo $text;
     file_put_contents("import_job.log", $text, FILE_APPEND);
   }
   
   public function success($job){
     echo "\n ======== success  ======================\n";
   }
   
   public function failed($job){
     echo "\n ======== failed ======================\n";
   }
     
   public function retry($job){
     echo "\n ========= retry ===================== \n" ;
//     $siteconfig = $this->siteconfig;
//     if($siteconfig && $siteconfig->lastImport()){
//        if($siteconfig->lastImport()->status == ImportSiteHistory::PENDING ){
//          $import = $siteconfig->lastImport();
//          $import->status = ImportSiteHistory::START;
//          $import->save();
//        }
//     }
   }
 }