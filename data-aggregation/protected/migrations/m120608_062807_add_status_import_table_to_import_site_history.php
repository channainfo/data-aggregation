<?php

class m120608_062807_add_status_import_table_to_import_site_history extends CDbMigration
{
  private $_tableName = "da_import_site_histories";
	public function up(){
    $this->addColumn($this->_tableName, "importing_table", "string");
    $this->addColumn($this->_tableName, "total_record", "integer(10)");
    $this->addColumn($this->_tableName, "current_record", "integer(10)");
	}

	public function down()	{
		$this->dropColumn($this->_tableName, "importing_table");
    $this->dropColumn($this->_tableName, "total_record");
    $this->dropColumn($this->_tableName, "current_record");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}