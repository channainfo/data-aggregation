<?php

class m120217_073957_create_da_dj_jobs_table extends CDbMigration
{
	public function up(){
    $this->createTable("da_djjobs", array(
      "id" => "pk",
      "handler" => "string",
      "queue" => "string",
      "attempts" => "int(11) ",
      "run_at" => "datetime",
      "locked_at" =>  "datetime",
      "locked_by" => "string" ,
      "failed_at" => "datetime",
      "error" => "datetime",
      "created_at" => "datetime"
    ));
	}
	public function down(){
    $this->dropTable("da_djjobs");
  }
}