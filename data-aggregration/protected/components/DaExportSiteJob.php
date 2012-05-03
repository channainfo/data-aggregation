<?php
 class DaExportSiteJob {
   public $exportId ;
   public function __construct($exportHistoryId) {
     $this->exportId = $exportHistoryId;
   }
   
   public function perform(){
     $export = new DaExportSite($this->exportId, Yii::app()->db);
     $export->start();
   }
   
   
 }