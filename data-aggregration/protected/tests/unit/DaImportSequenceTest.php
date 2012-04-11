<?php
 class DaImportSequenceTest extends CDbTestCase {
   
   public $site;
   public $import ;
   
   public function __construct() {
     $this->cleanUp();
   }
   
   public function setUp() {
     parent::setUp();
     
     $this->site = new SiteConfig();
     $this->site->attributes = array(
         "code" => "1901",
         "name" => "site2",
         "db" => "site2",
         "user" => "sa",
         "password" => "123456",
         "host" => "localhost"
     );
     $this->site->save();
     print_r($this->site->getErrors());
     echo $this->site->id;
     print_r($this->site->attributes);
     
     $this->import = new ImportSiteHistory();
     $this->import->attributes = array(
         "status" => ImportSiteHistory::START,
         "siteconfig_id" => $this->site->id 
     );
     $this->import->save() ;
     
   }
   
   public function testImportIMain(){
     $import = new DaImportSequence(Yii::app()->db, $this->site->code );
     $errors = $import->importIMain("tbleimain");
     print_r($errors);
   }
   
   public function cleanUp(){
     $db = Yii::app()->db;
     
     DaDbHelper::startIgnoringForeignKey($db);
     $sqls = array("da_reject_patients", "da_import_site_histories", "da_siteconfigs");
     foreach($sqls as $sql){
       $db->createCommand("truncate table {$sql} ")->execute();
     }
     DaDbHelper::endIgnoringForeignKey($db);
     
   }
 }