<?php
  class DaGenderError extends DaError {
    private $gender ;
    private $type ;
    const ERR_EMPTY = 1;
    const ERR_NONE = 0;
    const ERR_INVALID = 2;


    public function __construct($gender) {
      $this->gender = $gender;
      $this->type = self::ERR_NONE ;
      $this->validate();
    }
    public function getErrorType(){
      return $this->type;
    }
    
    public function __toString() {
      return $this->gender;
    }
    
    private function validate(){
      $gender = trim($this->gender);
      if($gender == "")
        $this->type = self::ERR_EMPTY;
      
      
      elseif($gender != "Female" && $gender !="Male" )
        $this->type = self::ERR_INVALID;
      
      
      return $this->type;
    }
    
  }