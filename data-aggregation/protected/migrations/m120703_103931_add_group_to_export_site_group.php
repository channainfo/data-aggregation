<?php

class m120703_103931_add_group_to_export_site_group extends CDbMigration{
  private $_tableName = "da_export_history" ;
  
  public function up(){
    $this->addColumn($this->_tableName , "group", "string");
	}
  
	public function down(){
    $this->dropColumn($this->_tableName , "group");
	}
}