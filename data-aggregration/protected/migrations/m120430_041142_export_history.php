<?php

class m120430_041142_export_history extends CDbMigration
{
  private $_tableName = "da_export_history" ;
	public function up(){
    $this->createTable($this->_tableName, array(
        "id" => "pk",
        "date_start"   => "datetime",
        "date_end"     => "datetime",
        "reversable"   => "int(1)",
        "sites"    => "text",
        "status"       => "int(4)",
        "job_id"    => "int(11)" ,
        "table_list"    => "text",
        "file"         => "string",
        "created_at"   => "datetime",
        "modified_at"  => "datetime",
        "all_site" => "int(1)",
        "all_table" => "int(1)",
        "site_text" => "text"
    ));
	}

	public function down(){
    $this->dropTable($this->_tableName);
	}
}