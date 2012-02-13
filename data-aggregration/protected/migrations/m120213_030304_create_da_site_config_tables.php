<?php

class m120213_030304_create_da_site_config_tables extends CDbMigration
{
	public function up(){
    $this->createTable("da_site_configs", array(
        "id" => "pk",
        "code" => "string not null",
        "name" => "string",
        "host" => "string not null",
        "user" => "string not null",
        "password" => "string",
        "db" => "string not null",
        "created_at" => "datetime",
        "modified_at" => "datetime"
    ));
	}

	public function down(){
    $this->dropTable("da_site_configs");
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