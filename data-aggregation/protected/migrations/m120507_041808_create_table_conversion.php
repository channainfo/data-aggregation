<?php

class m120507_041808_create_table_conversion extends CDbMigration {
  private $_tableName = "da_conversion" ;
	/**
   * 
   */
  public function up(){
    $this->createTable($this->_tableName, array(
        "id"            => "pk",
        "date_start"    => "datetime",
        "date_end"      => "datetime",
        "status"        => "int(4)",
        "job_id"        => "int(11)" ,
        "src"           => "string",
        "des"           => "string",
        "message"       => "text" ,
        "created_at"    => "datetime",
        "modified_at"   => "datetime"
    ));
	}
  /**
   *  
   */
	public function down(){
    $this->dropTable($this->_tableName);
	}
}