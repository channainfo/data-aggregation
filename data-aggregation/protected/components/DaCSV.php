<?php
 class DaCSV {
   private $file;
   private $handle ;
   public function __construct($file) {
      $this->file = $file ;
      $this->handle = fopen($this->file, 'w');
   }
   public function addRow($row, $delimiter = ",", $enclosure='"'){
     fputcsv($this->handle, $row, $delimiter,$enclosure);
   }
   
   public function addRows($rows, $delimiter=",",  $enclosure='""' ){
     foreach($rows as $row){
        addRow($row, $delimiter , $enclosure);
     }
   }
   
   public function generate(){
     fclose($this->handle);
   }
   
   
 }