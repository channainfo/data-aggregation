<?php
 class DaExportSiteJob {
   public $exportId ;
   public function __construct($exportHistoryId) {
     $this->exportId = $exportHistoryId;
   }
   
   public function perform(){
     $export = new DaExportSite(Yii::app()->db);
     $export->export($this->exportId);
   }
   
   
 }