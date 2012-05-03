<?php
 /**
  * @property ExportHistory $export
  * @property CDbConnection $db 
  */
 class DaExportSite {
   public $export ;
   public $db ;
   private $sitecodes;
   private $serial ;
   private $files = array();
   
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
   
   public function createZip(){
     $file = date("Y-m-d-H-i-s")."-".$this->serial.".zip";
     $archive = new DaArchive();
     $zipfile = DaConfig::pathDataStoreExport().$file;
     $archive->createZip($this->files, $zipfile  );
     return $file ;
   }
   
   public function cleanFiles(){
     foreach($this->files as $file){
       @unlink($file);
     }
   }
   
   public function start(){
     $this->serial = time();
     foreach($this->export->getTableList() as $tableName => $columns){
       $this->exportTable($tableName, array_keys($columns));
     }
     $zipfile = $this->createZip();
     $this->export->file = $zipfile;
     $this->export->status = ExportHistory::SUCCESS;
     $this->export->save();
   }
   
   public function getSiteCodeString(){
     $sitecodes = array(-1);
     foreach($this->export->getSites() as $site){
       $sitecodes[] = "'{$site->code}'";
     }
     return implode(",", $sitecodes);
   }
  
   /**
    * Generate 
    * @param string $tableName
    * @param array $columns 
    */
   public function exportTable($tableName, $columns){
    $where = "";
    if($this->export->all_site && $this->isSiteTable($tableName)  ){
      $sitecodestring = $this->getSiteCodeString();
      $where = " WHERE id IN ({$sitecodestring})" ;
    } 
    
    $filename = $tableName . "-" . date("Y-m-d-H-i-s")."-".$this->serial.".csv";
    
    $tmppath = DaConfig::pathDataStoreExport()."tmp/" ;
    DaConfig::mkDir($tmppath);
    
    $fullpath = $tmppath.$filename;
    
    $sql = " SELECT {$this->getColumnsHeader($columns)} " .
           " \n UNION ALL " .
           " \n SELECT {$this->getColumnsSelect($tableName, $columns)} " .
           " \n FROM {$tableName} {$where} " .
           " \n INTO OUTFILE '" . addslashes($fullpath) ."' ".
           " \n FIELDS TERMINATED BY ','  OPTIONALLY ENCLOSED BY '\"' ";
        
    $command =$this->db->createCommand($sql);
    $command->execute();
    $this->files [] = $fullpath ;
   }
   
   public function isColumnsAnonymize($tableName, $columns, $column){
     for($i=0; $i< count($columns) ; $i++){
        if($columns[$i]== $column and $i == 0)
          return true;
     }
     return false ;
   }
  /**
   *
   * @param array $columns
   * @return string 
   */
   public function getColumnsHeader($columns){
     $headers = array() ;
     foreach($columns as $column){
       $headers[] = "'{$column}'";
     }
     return implode(" , ", $headers);
   }
   /**
    *
    * @param string $tableName
    * @param array $columns
    * @return string 
    */
   public function getColumnsSelect($tableName, $columns){
     $select = array();
     foreach($columns as $column){
       if($this->isColumnsAnonymize($tableName, $columns, $column))
          if($this->export->reversable == 1)      
            $select[] = "da_anonymize({$column}, 1)" ;
          else
            $select[] = "da_anonymize({$column}, 0)" ;
       else
         $select[] = $column;
     }
     return implode(", ", $select) ;
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