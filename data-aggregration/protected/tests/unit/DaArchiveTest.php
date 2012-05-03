<?php
 class DaArchiveTest extends CTestCase {
   public $filename = "testzip.zip" ;
   
   public function setUp(){
     $path =     $file = dirname(__FILE__)."/../data/";
     @unlink($path.$this->filename);
     
   }

   public function testCreateZip(){
     $archive = new DaArchive();
     $path =     $file = dirname(__FILE__)."/../data/";
     
     $srcfiles = array( $path."zip1.txt", 
                        $path."zip2.txt", 
                        $path."zip3.txt",
                        "not exist1" ,
                        "not exist2" ,
                        "not exist3" ,
                        "not exist4" ,
         ) ;

     $zipfile = $path.$this->filename;
     $archive->createZip($srcfiles, $zipfile);
     $this->assertEquals($archive->getZip()->numFiles, 3 );
     $this->assertEquals( realpath($archive->getZip()->filename),  realpath($zipfile) );   
   }
 }