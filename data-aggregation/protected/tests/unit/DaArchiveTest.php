<?php
 class DaArchiveTest extends CTestCase {
   public $zipfile ;
   public $srcfiles = array();
   public $path ;
   
   
   public function setUp(){
     $this->path =  dirname(__FILE__)."/../data/";
     $this->zipfile =  $this->path."test.zip" ;
     @unlink($this->zipfile);
     
     $this->srcfiles = array( $this->path."zip1.txt", 
                        $this->path."zip2.txt", 
                        $this->path."zip3.txt",
                        "not exist1" ,
                        "not exist2" ,
                        "not exist3" ,
                        "not exist4" ,
         ) ;
   }

   public function testCreateZip(){
     $archive = new DaArchive();
     $archive->createZip($this->srcfiles, $this->zipfile);
     
     $this->assertEquals($archive->getZip()->numFiles, 3 );
     $this->assertEquals( realpath($archive->getZip()->filename),  realpath($this->zipfile) );   
     
   }
   
   public function testExtractTo(){
     $archive = new DaArchive();
     // create zip
     $to = $this->path."extract/" ;
     $archive->createZip($this->srcfiles, $this->zipfile);
     
     @unlink($to."zip1.txt");
     @unlink($to."zip2.txt");
     @unlink($to."zip3.txt");
     
     $archive->extractZip($this->zipfile, $to );
     $this->assertEquals($archive->getZip()->numFiles, 3 );
     $this->assertEquals($this->checkFiles($to."zip1.txt"), true);
     $this->assertEquals($this->checkFiles($to."zip2.txt"), true);
     $this->assertEquals($this->checkFiles($to."zip3.txt"), true);
   }
   public function checkFiles($file){
     DaTool::p($file);
     return file_exists($file) ;
   }
 }