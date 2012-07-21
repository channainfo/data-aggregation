<?php

class m120209_094658_add_active_column_to_tbl_users extends CDbMigration
{
	public function up(){
    $this->addColumn("da_users", "active" , "boolean default true");
	}
	public function down(){
    $this->dropColumn("da_users", "active");
	}
}