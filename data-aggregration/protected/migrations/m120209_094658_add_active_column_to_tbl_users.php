<?php

class m120209_094658_add_active_column_to_tbl_users extends CDbMigration
{
	public function up(){
    $this->addColumn("tbl_users", "active" , "boolean default true");
	}
	public function down(){
    $this->dropColumn("tbl_users", "active");
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