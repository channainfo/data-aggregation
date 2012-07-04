<?php

class m120704_152929_create_site_code_and_name_for_import_site_histories extends CDbMigration{
  private $_tableName  = "da_import_site_histories" ;
  
  public function up()	{
    $this->addColumn( $this->_tableName, "site_code" , "string");
    $this->addColumn( $this->_tableName , "site_name" , "string");
    
	}

	public function down(){
		$this->dropColumn($this->_tableName, "site_code" );
    $this->dropColumn($this->_tableName, "site_name" );
    
	}
}
	