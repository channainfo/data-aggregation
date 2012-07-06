<?php
 class DaARTError extends DaError {
   private $art ;
   private $type ;
   const ERR_NONE = 0 ;
   const ERR_CHILD = 1 ;
   const ERR_ADULT = 2 ;
   const ERR_EMPTY = 3 ;
   
   public function __construct($art) {
      $this->type = self::ERR_NONE ;
      $this->art = $art;
      $this->validate();
   }
   private function validate(){  
      $art = trim($this->art);
      if(empty($art))
        $this->type = self::ERR_EMPTY ;
      elseif(strtolower($art[0])== 'p'){
        $code =substr($art, 1);
        if(strlen($code) != 9){
          $this->type = self::ERR_CHILD ;
        }
      }
      else if(strlen($art) != 9){
        $this->type = self::ERR_ADULT ;
      }
      return $this->type ;    
   }
   
   public function getErrorType(){
      return $this->type ;
   }
   
   public function getART(){
     return $this->art ;
   }
   
   
 }