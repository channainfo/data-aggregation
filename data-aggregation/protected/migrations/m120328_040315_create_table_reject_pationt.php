<?php

class m120328_040315_create_table_reject_pationt extends CDbMigration
{
  public $_tableName = "da_reject_patients";
	public function up(){
    $this->createTable($this->_tableName, array(
        "id" => "pk",
        "tableName" => "string",
        "name" => "string" ,
        "code" => "int(4) DEFAULT 0 ",
        "message" => "text",
        "record" => "text",
        "err_records" => "text",
        "import_site_history_id" => "int(11) NOT NULL",
        "modified_at" => "datetime",
        "created_at" => "datetime"
    ));
	}

	public function down(){
    $this->dropTable($this->_tableName);
	}
}