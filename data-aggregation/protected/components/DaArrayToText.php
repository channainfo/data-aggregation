<?php
 class DaArrayToText {
   public $data ;
   
   public function __construct($data) {
     $this->data = $data ;
   }
   
   public function prepareContent($data, $t=0 ){
    $content = "" ;
    if(is_array($data)){
      foreach($data as $key => $value){
        if(is_array($value)){
          $content = $content . str_repeat("\t",$t) . "[{$key}]\n";
          $content = $content . $this->prepareContent($value, $t+1);
        }
        else
          $content = $content . str_repeat("\t",$t) . "{$key}={$value}\n";
      }
    }
    return $content ;
   }
   
   public function writeToFile($file){
     $content = $this->prepareContent($this->data);
     file_put_contents($file, $content);
     return $content;
   }
   
 }