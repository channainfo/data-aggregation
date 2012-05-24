<?php

class m120206_061319_create_users_table extends CDbMigration
{
	public function up()
	{
     $this->createTable('da_users', array(
         'id' => 'pk',
         'login' => 'string NOT NULL',
         'group_id' => 'int(11) NOT NULL' ,
         'password' => 'string NOT NULL' ,
         'email' => 'string' ,
         'salt' => 'string NOT NULL' ,
         'name' => 'string',
         'created_at' => 'datetime' ,
         'modified_at' => 'datetime' ,
         'last_login_at' => 'datetime' ,
       
        ));
	}

	public function down()
	{
    $this->dropTable("da_users");
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