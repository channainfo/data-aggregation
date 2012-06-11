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
	}

	public function down(){
    $this->dropTable($this->_tblName);
	}

}