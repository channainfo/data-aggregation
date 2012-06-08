<?php

class m120608_030812_add_column_status_to_siteconfig extends CDbMigration
{
	public function up(){
    $this->addColumn("da_siteconfigs", "status", "int(4) default 10");
    $this->addColumn("da_siteconfigs", "last_imported", "datetime");
    $this->addColumn("da_siteconfigs", "last_restored", "datetime");
	}
  
	public function down(){
    $this->dropColumn("da_siteconfigs", "status");
    $this->dropColumn("da_siteconfigs", "last_imported");
    $this->dropColumn("da_siteconfigs", "last_restored");
	}
  
  
}