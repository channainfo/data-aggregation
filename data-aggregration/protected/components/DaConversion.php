<?php
 /**
  * @property Conversion $conversion conversion object
  * @property CDbConnection $db database connection
  */
 class DaConversion {
   private $conversion ;
   private $db ;
   
   public function __construct($id, $db) {
     $conversion =  Conversion::model()->findByPk($id);
     if($conversion){
       $this->conversion = $conversion ;
       $this->db = $db ;
     }
   }
   /**
    * 
    */
   public function start(){
     
     $file = DaConfig::pathDataStore().$this->conversion->src ;
     
     //pathinfo($this->conversion->src);
     $dir = DaConfig::pathDataStore()."export/tmp/";
     DaConfig::mkDir($dir);
     
     $archive = new DaArchive();
     $archive->extractZip($file, $dir);
     echo "\n" ;
     for($i=0; $i< $archive->getZip()->numFiles ; $i++){
        $stat  = $archive->getZip()->statIndex($i) ;
        print_r($stat);
        $csvFile = $dir.$stat["name"];
        echo "file : {$csvFile} \n" ;
        $tableName = "da_tmp_{$i}" ;
        $this->processCSV($csvFile, $tableName);
        
        
     }
   }
   
   public function processCSV($csvFile, $tableName){
     if(file_exists($csvFile)){
        if (($handle = fopen($csvFile, "r")) !== FALSE) {
          while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
              $n = count($data);
              print_r($data);
              break ;
          }
          fclose($handle);
        }  
     }
     $this->createTempTable($data, $tableName);
   }
   
   public function createTempTable($columns, $tableName, $file){
     $fields = array();
    
     foreach($columns as $field){
       $fields[] = " {$field} text " ;
     }
     $field_str = implode(",", $fields);
     $sql = " CREATE TEMPORARY TABLE IF NOT EXISTS " . $tableName . "(".$field_str.")";
     $command = $this->db->createCommand($sql);
     $command->execute();
     
     $loadQuery = " LOAD DATA LOCAL INFILE '". $file ."' INTO TABLE  ". $tableName 
                . " fields terminated by ',' enclosed by '\"' lines terminated by '\n' "; 
     $command = $this->db->createCommand($loadQuery);
     $command->execute();
     
     
     $sql= " SELECT " . implode(", ", $fields).
           " \n UNION ALL " .
           " \n SELECT {$this->getColumnsSelect($tableName, $columns, $settings)} " .
           " \n FROM {$tableName} LIMIT 1, 18446744073709551615 " .
           " \n INTO OUTFILE '" . addslashes($fullpath) ."' ".
           " \n FIELDS TERMINATED BY ','  OPTIONALLY ENCLOSED BY '\"' ";
           
                
     
   }
   
   public function getColumnsSelect($tableName, $columns, $settings){
     $fields = array();
     foreach($columns as $column){
       if(isset($settings[$tableName][$column]))
         $fields[] = " da_reverse({$column})" ;
       else
         $fields[] = $column ;
     }
     return implode(", ", $fields);
   }
   
 }
 
 
 
 
 
 
 
 
 
 
 