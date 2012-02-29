<?php

class m120228_044345_create_drug_control_table extends CDbMigration{
  public $_tblName = "da_drug_controls" ;
	public function up(){
    $this->createTable("{$this->_tblName}",array(
        "id" => "pk",
        "name" => "string",
        "description" => "text",
        "created_at" => "datetime",
        "modified_at" => "datetime"
    ) );
    
    $sql = "INSERT INTO {$this->_tblName} VALUES(NULL, :name, :description, NOW(), NOW())" ;
    $db = Yii::app()->db;
    
    $command = $db->createCommand($sql);
   
    $drugs = array(
        "3TC", "ABC", "AZT", "d4T", "ddl", "EFV", "IDV", "Kaletra(LPV/r)", 
        "LPV", "NFV", "NVP", "RTV", "SQV", "TDF"
    );
    
    foreach($drugs as $drug){
      $command->bindParam(":name", $drug, PDO::PARAM_STR);
      $command->bindParam(":description", $drug, PDO::PARAM_STR);
      $command->execute();
    }
	}

	public function down(){
    $this->dropTable($this->_tblName);
	}

}