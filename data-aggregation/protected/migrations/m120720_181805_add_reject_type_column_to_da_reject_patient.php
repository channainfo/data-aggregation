<?php

class m120720_181805_add_reject_type_column_to_da_reject_patient extends CDbMigration
{
  private $_tableName = "da_reject_patients" ; 
	public function up()	{
    $this->addColumn($this->_tableName, "reject_type" , "int(4) DEFAULT 1 ");
	}

	public function down(){
		$this->dropColumn($this->_tableName, "reject_type" );    
	}
}