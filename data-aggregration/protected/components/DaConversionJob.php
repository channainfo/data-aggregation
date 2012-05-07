<?php
  class DaConversionJob {
    private $conversionId ;
    public function __construct($idConversion) {
      $this->conversionId = $idConversion ;
    }
    
    public function perform(){
      $daconversion = new DaConversion($this->conversionId, Yii::app()->db);
      $daconversion->start();
    }
    
    
    
  }