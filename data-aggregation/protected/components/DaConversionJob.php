<?php
  class DaConversionJob {
    
    private $conversionId ;
    public function __construct($idConversion) {
      $this->conversionId = $idConversion ;
    }
    
    public function perform(){
      $export = new DaExportSite(Yii::app()->db);
      $export->reverse($this->conversionId);
    }
  }