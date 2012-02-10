<?php

class m120210_041210_create_user_group_table extends CDbMigration{
	public function up(){
    $this->createTable("da_groups", array(
        "id" => "pk",
        "name" => "string not null",
        "description" => "text"
    ));
	}

	public function down(){
    $this->dropTable("da_groups");
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