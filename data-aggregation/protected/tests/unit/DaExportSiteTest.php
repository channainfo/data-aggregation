<?php
  class DaExportSiteTest extends CDbTestCase {
    public $site;
    public $exportHistory ;
    public $settings ;
    public $attributes ;



    public function setUp() {
      $this->settings = array('tblaimain' => array( 'clinicid','grou') ,
                              'tblcimain' => array( 'datevisit','dob','sex' ) ,
                              'tblcvmain' => array( 'clinicid','datevisit','typevisit') ,
                              'tblaifamily' => array( 'clinicid','relativespopart','age','hivstatus','status','mother','child','arv','oiart','receiarv','hostorytb','id' ) ,
                              'tblaiothpasmedical' => array( 'clinicid','hivrelatill','dateon','othnothiv','id' ) ,
                              'tblart' => array( 'clinicid','art' ) ,
                              'tblcicotrimo' => array( 'clinicid','startdate','stopdate','reasonstop','id' ) ,
                              'tblciothpastmedical' => array( 'clinicid','hiv','dateonset','id' ) ,
                              'tblcvarvoi' => array( 'arvoi','status','dat','reason','cid','id' ) ,
                              'tblcvtbdrugs' => array( 'arv','dose','quantity','freq','dat','reason','remark','cid' ) ,
                              'tbluser' => array( 'username','password','back','muser' ) );
      
      $this->attributes = array(
            "reversable" => ExportHistory::ANONYM_REVERSABLE,
            "all_site" => 1,
            "site_list" => array ( "2" => "1901 - Stung Treng hospotal" , "5" => "1209 - Clinic Chhouk Sar" ),
            "all_table" => 0 ,
            "table_list" => array(
                                  "tables" => array(
                                          "tblaimain" => 1 ,
                                          "tblcimain" => 1 ,
                                          "tblpatienttest" => 1 ,
                                          "tblavmain" => 1 ),

                                  "columns" => array(
                                          "tblaimain" => array (
                                                                "clinicid" => 1 ,
                                                                "grou" => 1 ,
                                                                "namecontps1" => 1 ,
                                                                "maritalstatus" => 1 ,
                                                                "namelocationhbc" => 1),

                                          "tblcimain" => array (
                                                                "clinicid" => 1 ,
                                                                "datevisit" => 1 ,
                                                                "dob" => 1 ,
                                                                "sex" => 1 ,
                                                                "addguardian" => 1 ,
                                                                "house" => 1 ),

                                          "tblpatienttest" => array(
                                                                "testid" => 1 ,
                                                                "clinicid" => 1 ,
                                                                "dat" => 1 ,
                                                                "cd4" => 1 ,
                                                                "cd" => 1 ,
                                                                "cd8" => 1 ),

                                          "tblavmain" => array (
                                                                "clinicid" => 1 ,
                                                                "resp" => 1 ,
                                                                "tbinfection" => 1 ,
                                                                "familynoyes" => 1 ,
                                                                "missarv" => 1 )
                                      )));
      
      $this->exportHistory = new ExportHistory();
      $this->exportHistory->setData($this->attributes);
      $this->exportHistory->save();
      return parent::setUp();
    }
    
    public function testIsColumnsAnonymize(){
      $exportSite = new DaExportSite(Yii::app()->db);
      //$exportSite->export($this->exportHistory->id);
      
      $result = $exportSite->isColumnsAnonymize("tblaimain", "clinicid", $this->settings);
      $this->assertEquals($result, true);
      
      $result = $exportSite->isColumnsAnonymize("tblaimain", "cl-not-exist", $this->settings);
      $this->assertEquals($result, false);
    }

    public function testGetColumnSelectReverse(){
      $settings = array(
          'tblaimain'   => array( 'clinicid' => 1,'grou' =>1),
          'tblcimain'   => array( 'datevisit' => 1,'dob' => 1,'sex' => 1 ) ,
          'tblcvmain'   => array( 'clinicid' => 1,'datevisit' => 1,'typevisit' => 1) ,
          'tblaifamily' => array( 'clinicid' => 1,'relativespopart' => 1) ,
          'tblaiothpasmedical' => array( 'clinicid' => 1,'hivrelatill' => 1,'dateon' => 1 ,'othnothiv' => 1,'id' => 1 ),
          'tblart' => array( 'clinicid' => 1,'art' => 1 ) 
          );
      $exportSite = new DaExportSite(Yii::app()->db);
      $sql = $exportSite->getColumnsSelectReverse("tblaimain", array("clinicid", "dateVisite","grou", "blah"), $settings);
      
      $result = "DECODE(UNHEX(clinicid), 'NCHADS_DA'), IFNULL(dateVisite, ''), DECODE(UNHEX(grou), 'NCHADS_DA'), IFNULL(blah, '')";
      $this->assertEquals($sql, $result);
          
    }
    
    public function testGetColumnsSelect(){
      $columns = array( 'clinicid','grou','namecontps1','maritalstatus','namelocationhbc','artnumber','idu','pretranditional' );
      
      //Anonymize reversable
      $exportSite = new DaExportSite(Yii::app()->db);
      $exportSite->export($this->exportHistory->id);
      
      $result = $exportSite->getColumnsSelect("tblaimain", $columns , $this->settings);
      
      $str = "HEX(ENCODE(clinicid, 'NCHADS_DA')), HEX(ENCODE(grou, 'NCHADS_DA')), IFNULL(namecontps1, ''), IFNULL(maritalstatus, ''), IFNULL(namelocationhbc, ''), IFNULL(artnumber, ''), IFNULL(idu, ''), IFNULL(pretranditional, '')";
      $this->assertEquals($result, $str);
      
      // Anonymize not reversable
      $attrs = array_merge($this->attributes, array("reversable" => ExportHistory::ANONYM_NOT_REVERSABLE));
      $history1 = new ExportHistory();
      $history1->setData($attrs);
      $history1->save();
      
      $exportSite = new DaExportSite(Yii::app()->db);
      $exportSite->export($history1->id);
      $result = $exportSite->getColumnsSelect("tblaimain", $columns , $this->settings);
      
      $str = "HEX(ENCODE(clinicid, clinicid)), HEX(ENCODE(grou, grou)), IFNULL(namecontps1, ''), IFNULL(maritalstatus, ''), IFNULL(namelocationhbc, ''), IFNULL(artnumber, ''), IFNULL(idu, ''), IFNULL(pretranditional, '')";
      $this->assertEquals($result, $str);
      
      
      // Normal( Without anonymization )
      $attrs = array_merge($this->attributes, array("reversable" => ExportHistory::NORMAL));
      $history1 = new ExportHistory();
      $history1->setData($attrs);
      $history1->save();
      
      $exportSite = new DaExportSite( Yii::app()->db);
      $exportSite->export($history1->id);
      $result = $exportSite->getColumnsSelect("tblaimain", $columns , $this->settings);
      
      $str = "IFNULL(clinicid, ''), IFNULL(grou, ''), IFNULL(namecontps1, ''), IFNULL(maritalstatus, ''), IFNULL(namelocationhbc, ''), IFNULL(artnumber, ''), IFNULL(idu, ''), IFNULL(pretranditional, '')";
      $this->assertEquals($result, $str);
      
    }
  }