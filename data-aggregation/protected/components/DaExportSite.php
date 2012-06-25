<?php
 /**
  * @property ExportHistory $export
  * @property CDbConnection $db 
  */
 class DaExportSite {
   public $export ;
   public $db ;
   private $files = array();
   private $metadata = array();
   
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
     $this->metadata["header_info"]["name"] = $this->export->getReversibleText();
   }
   
   public function writeHeaderDataConversion($settings){
     $this->metadata["header_info"]["export_id"] = $settings["header_info"]["export_id"] ;
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
   
   public function getColumnDefinition($csvFile){
     if (($handle = fopen($csvFile, "r")) !== FALSE){
        while (($rows = fgetcsv($handle, 1000, ",")) !== FALSE) {
            return $rows ;
        }
       fclose($handle);
     }
     return array();
   }
   
   public function processCSV($csvFile, $tableName, $settings){
     $rows = $this->getColumnDefinition($csvFile) ;
     $this->createTempTable($rows, $tableName);
     $this->loadIntoTable($rows, $tableName, $csvFile);
     $this->exportTable($tableName, $rows, $settings, true);
   }
   
   public function exportTable($tableName, $columns, $settings ,$reversible=true){
    $tmppath = DaConfig::pathDataStoreExport()."tmp/" ;
    DaConfig::mkDir($tmppath);
    $filename = $tableName . ".csv";
   
    $fullpath = $tmppath.$filename;
    @unlink($fullpath); // silently remove the old file
    
    $where = "" ;
    if($reversible == false){
      if(!$this->export->all_site && $this->isSiteTable($tableName)  ){
        $sitecodestring = $this->getSiteCodeString();
        $where = " \n WHERE id IN ({$sitecodestring})" ;
      }
      $from = $tableName ;
      $selecedFields = $this->getColumnsSelect($tableName, $columns, $settings);
      
    }
    else{
       $from = $this->generateTempTable($tableName) ; // Get table from temp table." LIMIT 1, 18446744073709551615 " ;
       $selecedFields = $this->getColumnsSelectReverse($tableName, $columns,$settings);
    }
    $columnHeader ="{$this->getColumnsHeader($columns)}";
    
    $order = "" ;
    if(array_search("id", $columns))
       $order = " \n ORDER BY id DESC" ;
    
    $sql = " \n SELECT  {$columnHeader} " .
           " \n UNION ALL " .
           " \n SELECT {$selecedFields} " .
           " \n FROM {$from} " .
           " {$where} " .
           " {$order} ".        
           " \n INTO OUTFILE '" . addslashes($fullpath) ."' ".
           " \n FIELDS ESCAPED BY '' TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\\r\\n' " ;         
    $command =$this->db->createCommand($sql);
    $command->execute();
    $this->files [] = $fullpath ;
   }
    /**
    * Generate string with encoding command in mysql 
    * @param string $tableName table name that contains config inside the $settings variable
    * @param array $columns columns array contains config inside the $settings variable
    * @param array $settings config in the form of $settings = array(table => $columns,) 
    * @return string comma separated string used in the SELECT returnValue FROM $tableName
    */

   public function getColumnsSelect($tableName, $columns, $settings){
     $select = array();
     foreach($columns as $column){
       // Is anonymize export type, check if column is set to anonymize, otherwise it is its self
       if($this->export->reversable == ExportHistory::ANONYM_REVERSABLE  || $this->export->reversable == ExportHistory::ANONYM_NOT_REVERSABLE ){
          if($this->isColumnsAnonymize($tableName, $column, $settings)){
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
            $select[] = $this->normalColumnExport($column);
       }
       else
         $select[] = $this->normalColumnExport($column);
     }
     return implode(", ", $select) ;
   }
   /**
    *
    * @param type $column column name
    * @return string return the column to export
    */
   public function normalColumnExport($column){
      return "IFNULL($column, '')" ;
   }
   
   /**
    * Generate string with decoding command in mysql
    * @param string $tableName table name that contains config inside the $settings variable
    * @param array $columns columns array contains config inside the $settings variable
    * @param array $settings config in the form of $settings = array(table => $columns,) 
    * @return string comma separated string used in the SELECT returnValue FROM $tableName
    */
   public function getColumnsSelectReverse($tableName, $columns, $settings){
     $fields = array();
     foreach($columns as $column){
       if(isset($settings[$tableName][$column]))
         $fields[] = $this->decodeAnonymize($column) ; //" da_reverse({$column})" ;
       else
         $fields[] = $this->normalColumnExport($column);
     }
     return implode(", ", $fields);
   }
   /**
    * Reverse to all encoding columns in csv file. 
    * @param integer $conversionId Id of conversion.
    */ 
   public function reverse($conversionId){
     $conversion =  Conversion::model()->findByPk($conversionId);
     $conversion->status = Conversion::PENDING ;
     $conversion->save(); 
     
     $file = DaConfig::pathDataStore().$conversion->src ;
     //pathinfo($this->conversion->src);
     $dir = DaConfig::pathDataStore()."export/extract/";
     DaConfig::mkDir($dir);
     
     $archive = new DaArchive();
     $archive->extractZip($file, $dir);
     $settings = array();
     $config = $dir.DaConfig::META_EXPORT_FN ;
     if(file_exists($config)){
       $settings = parse_ini_file($config, true);
       unlink($config);
     }
     else{
        $msg = "Could not do conversion because system could not find config.ini metadata "  ;
        DaTool::p($msg);
        $this->updateConversion($conversion, Conversion::FAILED , $msg) ;
        return ;
     }
     if($settings["header_info"]["type"] != ExportHistory::ANONYM_REVERSABLE){
         $msg = "Could not do conversion because the type of exported zip file was : " . ExportHistory::ReversableText($settings["header_info"]["type"]) ;
         DaTool::p($msg);
         $this->updateConversion($conversion, Conversion::FAILED , $msg) ;
         return ;
     }

     $this->writeHeaderDataConversion($settings); 
     for($i=0; $i< $archive->getZip()->numFiles ; $i++){
        $stat  = $archive->getZip()->statIndex($i) ;
        if($stat["name"] != DaConfig::META_EXPORT_FN ){
          $csvFile = $dir.$stat["name"];    
          $tableName = basename($csvFile, ".csv");
          $this->processCSV($csvFile, $tableName, $settings);
          unlink($csvFile);
        }
     }

     $this->createMetaFile();
     $zipfile = $this->createZip();
     $this->updateConversion($conversion, Conversion::SUCCESS , "", $zipfile ) ;
     $this->cleanFiles();
   }
   /**
    * update Conversion 
    * @param Conversion $conversion
    * @param integer $status
    * @param string $message
    * @param string $file  
    */
   public function updateConversion($conversion, $status, $message="", $file ="" ){
     $conversion->status   = $status ;
     $conversion->des      = $file ;
     $conversion->message  = $message ;
     $conversion->date_end = DaDbWrapper::now();
     $conversion->save();
   } 
   /**
    * Generate temporary table in the database.
    * @param string $tableName
    * @return string 
    */
   public function generateTempTable($tableName){
     return "da_temp_".$tableName;
   }
   /**
    * 
    * @param integer $exportId 
    */
   public function export($exportId){
     $this->export = ExportHistory::model()->findByPk($exportId);    
     $this->export->status = ExportHistory::PENDING ;
     $this->export->save();
     
     $this->writeHeaderDataExport();
     $settings = DaConfig::importSetting();
     
     foreach($this->export->getTableList() as $tableName => $columns){
       $this->exportTable($tableName, array_keys($columns), $settings, false);
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
     foreach($this->export->getSites() as $code => $site_desc){
       $sitecodes[] = "'{$code}'";
     }
     return implode(",", $sitecodes);
   }
   /**
    * Check if the column $column is anonymized 
    * @param string $tableName the table name used to search in $settings 
    * @param string $column the column name to check
    * @param array  $settings The config in the form $settings\
    * 
    */
   public function isColumnsAnonymize($tableName, $column, $settings){
    if($settings && isset($settings[$tableName])){
      if(array_search($column, $settings[$tableName]) !==false){
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
    * Check to see table site table(not fixed table)
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
 
 