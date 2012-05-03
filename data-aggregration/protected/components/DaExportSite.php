<?php
 /**
  * @property ExportHistory $export
  * @property CDbConnection $db 
  */
 class DaExportSite {
   public $export ;
   public $db ;
   private $sitecodes;
   
   /**
    *
    * @param integer $exportId
    * @param CDbConnection $db 
    */
   public function __construct($exportId, $db) {
     $this->export = ExportHistory::model()->findByPk($exportId);
     $this->db = $db ;
     
     foreach($this->export->getSites() as $site ){
       $this->sitecodes[] = "'$site->code'" ;
     }
   }
   public function start(){
     foreach($this->export->getTableList() as $tableName => $columns){
       $sql = $this->getQuery($tableName, array_key($columns));
     }
   }
   public function getQuery($table, $columns){
     $sql = " SELECT ".implode(", ", $columns). " FROM {$table} ";
     if($this->isSiteTable($table)){
       if(!$this->export->all_site){
          $sitecodes = implode(",", $this->sitecodes);
          $sql .= " WHERE ID in ({$sitecodes})";
       }
     }
     return $sql ;
   }
   /**
    *
    * @param string $table
    * @return boolean 
    */
   public function isSiteTable($table){
     $configs = DaConfig::importConfig();
     if(isset($configs["tables"][$table]))
       return true;
     return false ;
   }
   
   
   
 }