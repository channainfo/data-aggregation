<?php
  class BackupTest extends CDbTestCase{
    public $attributes = null ;
    
    public function setUp(){
      $site = new SiteConfig();
      $site->setAttributes(array(
          "id" =>10,
          "db" => "db",
          "host" => "blah",
          "user" => "root",
          "password" => "ddd"
      ));
      $site->save();
      
      
      $this->attributes = array(
          "filename" => "backup.bak",
          "siteconfig_id" => $site->id ,
          "status" => 1
      );
    }
    
    
    public function testCreateBackup(){
      $model = new Backup();
      $model->deleteAll();
      $model->setAttributes($this->attributes);
      $state = $model->save();
      $this->assertEquals($state, true);
    }
    
    public function testValidateFileType(){
      $model = new Backup();
      $this->attributes["filename"] = "blah.blk";
      $model->setAttributes($this->attributes);
      $this->assertEquals($model->save(), false);
    }
    
    public function testFileType(){
      $model = new Backup();
      
      $model->filename = "this.bak";
      //with .bak file type
      $return = $model->fileType("filename", array("type" => ".bak"));
      $this->assertEquals($return, true);
      
      //with .ba file type
      $return = $model->fileType("filename", array("type" => ".ba"));
      $this->assertEquals($return, false);
    }
  }
