<?php

class m120206_061319_create_users_table extends CDbMigration
{
	public function up()
	{
     $this->createTable('tbl_users', array(
         'id' => 'pk',
         'login' => 'string NOT NULL',
         'role' => 'int(11) NOT NULL' ,
         'password' => 'string NOT NULL' ,
         'email' => 'string NOT NULL' ,
         'salt' => 'string NOT NULL' ,
         'name' => 'string NOT NULL',
         'created_at' => 'datetime' ,
         'modified_at' => 'datetime' ,
         'last_login_at' => 'datetime' ,
       
        ));
	}

	public function down()
	{
    $this->dropTable("tbl_users");
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