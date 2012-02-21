<?php

class m120215_074836_create_backup extends CDbMigration
{
	public function up(){
    $this->createTable("da_backups", array(
        "id" => "pk",
        "filename" => "string NOT NULL",
        "status" => "int(4) DEFAULT 0 ",
        "siteconfig_id" => "int(11) NOT NULL",
        "duration" => "int(4)",
        "reason" => "text",
        "modified_at" => "datetime",
        "created_at" => "datetime"
    ));
	}

	public function down(){
    $this->dropTable("da_backups");
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