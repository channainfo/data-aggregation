<?php
 class DaArrayHelper implements Iterator {
   public $data = array();
   public $position = 0 ;
   
   public function __construct($array) {
     $this->data = $array;
     $this->rewind();
   }
   /**
    *
    * @param boolean $skipEmpty
    * @return \DaArrayHelper 
    */
   public function symbolize($skipEmpty=true){
     $result = array();
     foreach($this->data as $value){
       if($skipEmpty == true && empty($value) )
         continue;
       
       $result[] = ":{$value}" ;
     }
     return new DaArrayHelper($result);
   }
   /**
    *
    * @return \DaArrayHelper 
    */
   public function lowerCase(){
     $result = array();
     foreach($this->data as $value){
       $result[] = strtolower($value);
     }
     return new DaArrayHelper($result);
   }
   
   public function join($glue, $skipEmpty=true){
     $result = array();
     foreach($this->data as $value){
       if($skipEmpty && empty($value))
         continue;
       $result[] = $value;
     }
     return implode($glue, $result);
   }
   
   public function key(){
     return $this->position;
   }
   
   public function rewind() {
     $this->position = 0;
   }
   
   public function next(){
     ++$this->position ;
   }
   public function current() {
     return $this->data[$this->position];
   }

   public function valid() {
    return isset($this->data[$this->position]);
   }
   
   
 }