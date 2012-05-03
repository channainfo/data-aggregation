<?php
 /**
  * @property ZipArchive $zip 
  */
 class DaArchive  {
   private $zip ;
   
   public function createZip($srcfiles, $zipfile){
     $this->zip = new ZipArchive();
     
     if ($this->zip->open($zipfile, ZIPARCHIVE::CREATE)!==TRUE) {
         throw new Exception("Could not create file : {$zipfile}");
     }
     foreach($srcfiles as $filename){
       if(file_exists($filename))
        $this->zip->addFile($filename, basename($filename));
       else
         echo "file : " . $filename ." was not added coz it does not exist : " ;
     }
   }
   /**
    *
    * @return ZipArchive 
    */
   public function getZip(){
     return $this->zip ;
   }
   
   public function zipInfo(){
    echo "numFiles: " . $this->zip->numFiles . "\n";
    echo "status: " . $this->zip->status  . "\n";
    echo "statusSys: " . $this->zip->statusSys . "\n";
    echo "filename: " . $this->zip->filename . "\n";
   }
   
   public function __destruct() {
     $this->zip->close();
   }
   
 }