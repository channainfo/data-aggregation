<?php
  class DaErrorInjection {
    public $errors = array();
    
    public function addAiMain($value){
       $this->addControl("AiMain", $value);
    }
    
    public function addCiMain($value){
      $this->addControl("CiMain", $value);
    }
    
    public function addAvMain($value){
      $this->addControl("AvMain", $value);
    }
    
    public function addCvMain($value){
      $this->addControl("CvMain", $value);
    }
    
    public function addART($value){
      $this->addControl("ART", $value);
    }
    
    public function addAvLostDead($value){
      $this->addControl("AvLostDead", $value);
    }
    
    public function addCvLostDead($value){
      $this->addControl("CvLostDead", $value);
    }
    
    public function addAvArv($value){
      $this->addControl("AvArv", $value);
    }
    
    public function addCvArv($value){
      $this->addControl("CvArv", $value);
    }
    
    public function addControl($key, $value){
      $this->errors[$key][] = $value;
    }
    
    
  }