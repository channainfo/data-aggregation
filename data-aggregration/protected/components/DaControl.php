<?php
  abstract class DaControl {
    public $record = array();
    public $errors = array();
    
    
    public function __construct($record=array()) {
      $this->setRecord($record);
    }
   
    /**
     *
     * @param array $record 
     */
    public function setRecord($record){
      $this->errors = array();
      $this->record = $record;
    }
    /**
     *
     * @return string 
     */
    public function getIdByName(){
       return $this->record[$this->key]; 
    }
    /**
     *
     * @return array 
     */
    public function getErrors(){
      return $this->errors;
    }

    public function recordsError(){
      return array();
    }
    
    public function addError($message){
      $this->errors[] = $message ;
    }
    
    /**
     * throws DaInvalidControlException 
     */
    abstract public function check($option=array());
    
  }
