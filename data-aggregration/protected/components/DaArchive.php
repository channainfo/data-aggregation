<?php
 /**
  * @property ZipArchive $zip 
  */
 class DaArchive  {
   private $zip ;
   
   public function __construct() {
     $this->zip = new ZipArchive();
   }
   
   public function createZip($srcfiles, $zipfile){
     if ($this->zip->open($zipfile, ZIPARCHIVE::CREATE)!==TRUE) {
         throw new Exception("Could not create file : {$zipfile}");
     }
     foreach($srcfiles as $filename){
       if(file_exists($filename)){
        DaTool::p("File : " . $filename . "added to zip"); 
        $this->zip->addFile($filename, basename($filename));
       }
       else
         DaTool::p("file : " . $filename ." was not added coz it does not exist : ") ;
     }
   }
   /**
    *
    * @param string $src location of zip
    * @param string $to  destination file
    * @throws Exception  
    */
   public function extractZip($src, $to){
     if ($this->zip->open($src) === TRUE) {
        DaConfig::mkDir($to);
        $this->zip->extractTo($to);
     }
     else   
      throw new Exception("Could not open :". $src);
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