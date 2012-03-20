<?php
  abstract class DaControl {
    public $record = array();
    
    public function __construct($record=array()) {
      $this->record = $record;
    }
   
    public function setRecord($record){
      $this->record = $record;
    }
    /**
     * throws DaInvalidControlException 
     */
    
    abstract public function check($option=array());
    
  }
