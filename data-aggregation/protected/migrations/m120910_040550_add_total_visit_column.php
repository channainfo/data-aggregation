<?php

class m120910_040550_add_total_visit_column extends CDbMigration
{
	private $_tableName = "da_import_site_histories" ; 
	public function up()	{
    $this->addColumn($this->_tableName, "total_visit" , "int(11) DEFAULT 0 ");
	}

	public function down(){
		$this->dropColumn($this->_tableName, "total_visit" );    
	}
}