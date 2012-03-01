<?php

class m120223_080443_add_foreigkey_conttraint_to_da_backups_table extends CDbMigration{
  public $_fk_key_name = "fk_da_bakups_siteconfig_id";
  private $_tableForeignKey = "da_backups" ;

  public function up(){
    $this->addForeignKey($this->_fk_key_name, "{$this->_tableForeignKey}", "siteconfig_id", "da_siteconfigs", "id");
	}

	public function down(){
    $this->dropForeignKey($this->_fk_key_name, "{$this->_tableForeignKey}");
	}
}
	