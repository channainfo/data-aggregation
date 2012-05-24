<?php

class m120217_073957_create_da_dj_jobs_table extends CDbMigration
{
	public function up(){
    $this->createTable("da_djjobs", array(
      "id" => "pk",
      "handler" => "text NOT NULL",
      "queue" => "string DEFAULT 'default' ",
      "attempts" => "int(11) DEFAULT 0 ",
      "run_at" => "datetime",
      "locked_at" =>  "datetime",
      "locked_by" => "string" ,
      "failed_at" => "datetime",
      "error" => "datetime",
      "created_at" => "datetime NOT NULL "
    ));
	}
	public function down(){
    $this->dropTable("da_djjobs");
  }
}