<?php
  abstract class DaControl {
    public $record = array();
    public $errors = array();
    
    
    public function __construct($record=array()) {
      $this->record = $record;
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
    
    /**
     *
     * @return string 
     */
    public function getIdByIndex(){
       return $this->record[$this->index];
    }
    
    public function addError($message){
      $this->errors[] = $message ;
    }
    
    /**
     * throws DaInvalidControlException 
     */
    abstract public function check($option=array());
    
  }
