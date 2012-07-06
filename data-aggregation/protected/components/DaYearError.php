<?php
 class DaYearError extends DaError {
   private $date;
   const ERR_1900 = 1;
   const ERR_EMPTY = 2;
   const ERR_NONE = 0 ;
   private $type;

   public function __construct($date) {
     $this->date = $date;
     $this->type = self::ERR_NONE ;
     $this->validate();
   }
   private function validate(){
     $year = DaTool::getYear($this->date);
     if($year == "1900")
       $this->type = self::ERR_1900 ;
     else if ($year == "")
       $this->type = self::ERR_EMPTY;
     return $this->type;  
   }
   
   public function getErrorType(){
     return $this->type ;
   }
   public function getDate(){
     return $this->date;
   }
 }