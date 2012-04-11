<?php
  /**
   *  
   */
  class DaOffYesNo extends DaControlElement{
    public $value = null;
    
    public function __construct($value) {
      $this->value = $value ;
    }
    
    public function valid(){
      return $this->value == "yes" ;
    }
    
  }