<?php
 class DaImportSiteJob {
   public $code = null ;
   public $siteconfig = null;
   
   public function __construct($sitecode) {
     $this->code = $sitecode ;
     $this->siteconfig = SiteConfig::model()->findByAttributes(array("code" => $this->code));
   }
   
   public function perform(){
     $importer  = new DaImportSequence(Yii::app()->db, $this->code);
     $importer->start();
   }
   
   public function success($job){
     echo "\n ======== success  ======================\n";
   }
   
   public function failed($job){
     echo "\n ======== failed ======================\n";
   }
   
   public function retry($job){
     echo "\n ========= retry ===================== \n" ;
     $siteconfig = $this->siteconfig;
     if($siteconfig && $siteconfig->lastImport()){
        if($siteconfig->lastImport()->status == ImportSiteHistory::PENDING ){
          $import = $siteconfig->lastImport();
          $import->status = ImportSiteHistory::START;
          $import->save();
        }
     }
   }
 }