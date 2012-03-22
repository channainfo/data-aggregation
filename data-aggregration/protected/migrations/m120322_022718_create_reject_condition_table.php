<?php

class m120322_022718_create_reject_condition_table extends CDbMigration {
  public $_tableName = "da_reject_conditions" ;
  public $_fk_key_name = "fk_da_reject_conditions_import_site_history_id" ;
  
  public function up(){
    $this->createTable($this->_tableName, array(
        "id" => "pk",
        "tableName" => "string NOT NULL",
        "code" => "int(4) DEFAULT 0 ",
        "message" => "text",
        "record" => "text",
        "import_site_history_id" => "int(11) NOT NULL",
        "modified_at" => "datetime",
        "created_at" => "datetime"
    ));
    $this->addForeignKey($this->_fk_key_name, "{$this->_tableName}", "import_site_history_id", "da_import_site_histories", "id", "CASCADE");
	}

	public function down(){
    $this->dropForeignKey($this->_fk_key_name, "{$this->_tableName}");
    $this->dropTable($this->_tableName);
	}
}