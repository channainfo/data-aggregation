<?php
 class DaImportSiteJob {
   public $code = null ;
   
   public function __construct($sitecode) {
     $this->code = $sitecode ;
   }

   public function perform(){
     $importer  = new DaImportSequence(Yii::app()->db, $this->code);
     $importer->start();
   }
   
 }