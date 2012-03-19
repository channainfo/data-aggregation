<?php
  abstract class DaControl {
    public $record = array();
    
    public function __construct($record) {
      $this->record = $record;
    }
    /**
     * throws DaInvalidControlException 
     */
    abstract public function check($option=array());
    
  }
