<?php
  class DaDbConnection {
    private $connection = false ;
    
    public function __construct() {
    }

    public function connect($host, $user, $pwd , $db){
      unset($this->connection);
      
      if( !empty($host) && !empty($user) && !empty($db)){
        $connOptions = array("Database"=>$db, "UID"=> $user, "PWD"=> $pwd);
        $this->connection = sqlsrv_connect($host, $connOptions);
      }
    }
    
    public function isConnected(){
      return $this->connection ;
    }
    
    public function __destruct() {
      unset($this->connection);
    }
    
    public function restoreFromBakFile($siteconfig_id){
      $siteconfig = SiteConfig::model()->with(array('backups'=>array('order'=>'backups.id desc')))->findByPk($siteconfig_id);
      
      echo "-----------------------------------" ;
      DaTool::debug( "name : ". $siteconfig->name);
      echo "-----------------------------------" ;
      //DaTool::debug($siteconfig,0,0);
      DaTool::debug($siteconfig->backups,0,0);
              
    }
    
    
  }
?>
