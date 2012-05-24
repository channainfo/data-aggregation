<?php

class m120427_041800_add_job_id_to_import_history extends CDbMigration
{
	public function up()	{
    $this->addColumn("da_import_site_histories", "job_id" , "integer");
    $this->addColumn("da_import_site_histories", "info" , "text");
    
	}

	public function down(){
		$this->dropColumn("da_import_site_histories", "job_id" );
    $this->dropColumn("da_import_site_histories", "info" );
    
	}

}