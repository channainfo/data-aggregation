<?php

class m120229_075950_create_import_site_histories_table extends CDbMigration{
  private $_import_site_histories = "da_import_site_histories";
	public function up(){
    $this->createTable($this->_import_site_histories, array(
        "id" => "pk",
        "status" => "int(4) DEFAULT 0 ",
        "siteconfig_id" => "int(11) NOT NULL",
        "duration" => "float",
        "reason" => "text",
        "modified_at" => "datetime",
        "created_at" => "datetime"
    ));
    $this->addForeignKey("fk_{$this->_import_site_histories}_siteconfig_id", "$this->_import_site_histories", "siteconfig_id", "da_siteconfigs", "id", "CASCADE");
  }

	public function down(){
    $this->dropForeignKey("fk_{$this->_import_site_histories}_siteconfig_id", $this->_import_site_histories );
		$this->dropTable($this->_import_site_histories);
	}

}