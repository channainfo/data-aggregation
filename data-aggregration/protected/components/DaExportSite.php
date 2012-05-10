<?php
 /**
  * @property ExportHistory $export
  * @property CDbConnection $db 
  */
 class DaExportSite {
   public $export ;
   public $db ;
   private $sitecodes;
   private $files = array();
   private $metadata = array();
   private $settings  = array();
   
   /**
    *
    * @param integer $exportId
    * @param CDbConnection $db 
    */
   public function __construct($db){
     $this->db = $db ;
   }
   
   public function writeHeaderDataExport(){
     $this->metadata["header_info"]["export_id"] = $this->export->id ;
     $this->metadata["header_info"]["type"] = $this->export->reversable ;
     $this->metadata["header_info"]["name"] = $this->export->getReversableText();
   }
   
   public function writeHeaderDataConversion(){
     $this->metadata["header_info"]["export_id"] = $this->settings["header_info"]["export_id"] ;
     $this->metadata["header_info"]["type"] = ExportHistory::NORMAL ;
     $this->metadata["header_info"]["name"] = ExportHistory::ReversableText(ExportHistory::NORMAL);
   }
   
   public function createZip(){
     $file = $this->metadata["header_info"]["export_id"]."-".ExportHistory::ReversableText($this->metadata["header_info"]["type"])."-".date("Y-m-d-H-i-s").".zip";
     $archive = new DaArchive();
     $zipfile = DaConfig::pathDataStoreExport().$file;
     $archive->createZip($this->files, $zipfile  );
     return $file ;
   }
   
   public function cleanFiles(){
     foreach($this->files as $file){
       @unlink($file);
     }
     $this->files = array();
   }
   
   /**
    *
    * @param array $columns
    * @param string $tableName
    * @param string $file 
    */
   public function createTempTable($columns, $tableName){
     $fields = array();
     $table = $this->generateTempTable($tableName);
     foreach($columns as $field){
       $fields[] = " {$field} text " ;
     }
     $field_str = implode("\n,", $fields);
     $sql = " CREATE TEMPORARY TABLE IF NOT EXISTS " . $table . "(".$field_str.")";
     $command = $this->db->createCommand($sql);
     $command->execute();
   }
   
   public function loadIntoTable($columns , $tableName, $file){
     $fileLoad = addslashes($file);
     $table = $this->generateTempTable($tableName);
     
     $fields = implode(",", $columns) ;
     DaTool::p("Load table :". $table);
     $sql = <<< EOD
     
     LOAD DATA INFILE  '$fileLoad' 
     INTO TABLE  $table 
     FIELDS TERMINATED BY ',' ENCLOSED BY '"' 
     LINES TERMINATED BY '\\n'
     ($fields) ;
     
EOD;

     $command = $this->db->createCommand($sql);
     $command->execute();
     DaTool::p("Remove first column :" . $table );
     $sql = "DELETE FROM ".$table. " LIMIT 1 " ;
     $command = $this->db->createCommand($sql);
     $command->execute();
   }
   
   public function processCSV($csvFile, $tableName){
      if (($handle = fopen($csvFile, "r")) !== FALSE) {
        while (($rows = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $this->createTempTable($rows, $tableName);
            $this->loadIntoTable($rows, $tableName, $csvFile);
            $this->exportTable($tableName, $rows, true);
            break ;
        }
       fclose($handle);
     }
   }
   
   public function exportTable($tableName, $columns, $reversible=true){
    $tmppath = DaConfig::pathDataStoreExport()."tmp/" ;
    DaConfig::mkDir($tmppath);
    $filename = $tableName . ".csv";
   
    $fullpath = $tmppath.$filename;
    @unlink($fullpath); // silently remove the old file
    
    $where = "" ;
    if($reversible == false){
      if($this->export->all_site && $this->isSiteTable($tableName)  ){
        $sitecodestring = $this->getSiteCodeString();
        $where = " WHERE id IN ({$sitecodestring})" ;
      }
      $from = $tableName ;
      $selecedFields = $this->getColumnsSelect($tableName, $columns);
      
    }
    else{
       $from = $this->generateTempTable($tableName) ; // Get table from temp table." LIMIT 1, 18446744073709551615 " ;
       $selecedFields = $this->getColumnsSelectReverse($tableName, $columns);
    }
    $columnHeader ="{$this->getColumnsHeader($columns)}";
    
    echo "\n".$columnHeader; 
    echo "\n\n";
    
    $sql = " SELECT  {$columnHeader} " .
           " \n UNION ALL " .
           " \n SELECT {$selecedFields} " .
           " \n FROM {$from} {$where} " .
           " \n INTO OUTFILE '" . addslashes($fullpath) ."' ".
           " \n FIELDS TERMINATED BY ','  OPTIONALLY ENCLOSED BY '\"' ";
           
     echo "\n $sql" ;      
    $command =$this->db->createCommand($sql);
    $command->execute();
    $this->files [] = $fullpath ;
   }

   public function getColumnsSelectReverse($tableName, $columns){
     $fields = array();
     foreach($columns as $column){
       if(isset($this->settings[$tableName][$column]))
         $fields[] = $this->decodeAnonymize($column) ; //" da_reverse({$column})" ;
       else
         $fields[] = $column ;
     }
     return implode(", ", $fields);
   }
   
   public function reverse($conversionId){
     $conversion =  Conversion::model()->findByPk($conversionId);
     $file = DaConfig::pathDataStore().$conversion->src ;
     //pathinfo($this->conversion->src);
     $dir = DaConfig::pathDataStore()."export/extract/";
     DaConfig::mkDir($dir);
     
     $archive = new DaArchive();
     $archive->extractZip($file, $dir);
     
     
     $config = $dir.DaConfig::META_EXPORT_FN ;
     if(file_exists($config)){
       $this->settings = parse_ini_file($config, true);
       unlink($config);
       if($this->settings["header_info"]["type"] != ExportHistory::ANONYM_REVERSABLE){
         $msg = "Could not do conversion because the type of exported zip file was : " . ExportHistory::ReversableText($this->settings["header_info"]["type"]) ;
         DaTool::p($msg);
         $this->updateConversion($conversion, Conversion::FAILED , $msg) ;
         exit ;
       }
     }
     else{
        $msg = "Could not do conversion because system could not find config.ini metadata "  ;
        DaTool::p($msg);
        $this->updateConversion($conversion, Conversion::FAILED , $msg) ;
        exit ;
     }

     $this->writeHeaderDataConversion(); 
     for($i=0; $i< $archive->getZip()->numFiles ; $i++){
        $stat  = $archive->getZip()->statIndex($i) ;
        if($stat["name"] != DaConfig::META_EXPORT_FN ){
          $csvFile = $dir.$stat["name"];    
          $tableName = basename($csvFile, ".csv");
          $this->processCSV($csvFile, $tableName);
          unlink($csvFile);
        }
     }
     
     
     $this->createMetaFile();
     $zipfile = $this->createZip();
     $this->updateConversion($conversion, Conversion::SUCCESS , "", $zipfile ) ;
     $this->cleanFiles();
   }

   public function updateConversion($conversion, $status, $message="", $file ="" ){
     $conversion->status   = $status ;
     $conversion->des      = $file ;
     $conversion->message  = $message ;
     $conversion->date_end = DaDbWrapper::now();
     $conversion->save();
   } 
   
   public function generateTempTable($tableName){
     return "da_temp_".$tableName;
   }
   
   public function export($exportId){
     $this->export = ExportHistory::model()->findByPk($exportId);    
     foreach($this->export->getSites() as $site )
       $this->sitecodes[] = "'$site->code'" ;   
     
     $this->writeHeaderDataExport();
     $this->settings = DaConfig::importSetting();
     
     foreach($this->export->getTableList() as $tableName => $columns){
       $this->exportTable($tableName, array_keys($columns), false);
     } 
     $this->createMetaFile();
     DaTool::p("Creating zip");
     $zipfile = $this->createZip();
     DaTool::p("Zip created : ". $zipfile );
     $this->export->file = $zipfile;
     $this->export->status = ExportHistory::SUCCESS;
     $this->export->date_end = DaDbWrapper::now();
     $this->export->save();
     $this->cleanFiles();
   }
   public function createMetaFile(){
     $file = DaConfig::pathDataStoreExport() . "tmp/" . DaConfig::META_EXPORT_FN ;
     $meta = new DaArrayToText($this->metadata);
     $meta->writeToFile($file);
     $this->files[] = $file ;
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
   
   
   public function isColumnsAnonymize($tableName, $column){
    if($this->settings && isset($this->settings[$tableName])){
      if(array_search($column, $this->settings[$tableName]) !==false){
        return true ;
      }
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
       // Is anonymize export type, check if column is set to anonymize, otherwise it is its self
       if($this->export->reversable == ExportHistory::ANONYM_REVERSABLE  || $this->export->reversable == ExportHistory::ANONYM_NOT_REVERSABLE ){
          if($this->isColumnsAnonymize($tableName, $column)){
              if( $this->export->reversable == ExportHistory::ANONYM_REVERSABLE ){
                $select[] = $this->encodeReversable($column); // "da_anonymize({$column}, 1)" ;
                $this->metadata[$tableName][$column] = 1 ;
              }
              else if ($this->export->reversable == ExportHistory::ANONYM_NOT_REVERSABLE){
                $select[] = $this->encodeNotReversable($column) ; // "da_anonymize({$column}, 0)" ;
                $this->metadata[$tableName][$column] = 0 ;
              }
          }
          else  
            $select[] = $column ;
       }
       else
         $select[] = $column;
     }
     return implode(", ", $select) ;
   }
   
   public function encodeReversable($column){
     return "HEX(ENCODE($column, '". DaConfig::PASS_KEY ."'))";
   }
   public function encodeNotReversable($column){
     return "HEX(ENCODE($column, $column))";
   }
   public function decodeAnonymize($column){
     return "DECODE(UNHEX($column), '". DaConfig::PASS_KEY ."')" ;
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
 
 