<?php
class m120223_032951_add_columns_to_tbl_tables extends CDbMigration{
  
  
  public function up(){
    $this->createTable(DaConfig::IMPORT_ESC_TABLE_NAME, array(
        "id" => "pk",
        "table_name"  =>   "string",
        "created_at"  =>   "datetime",
        "modified_at" =>  "datetime"
    ));
    
    $this->createTable(DaConfig::IMPORT_TABLE_NAME, array(
        "id" => "pk",
        "table_name" =>   "string",
        "created_at" =>   "datetime",
        "priority"    =>   "int(4) default 0",
        "modified_at" =>  "datetime"
    ));
  }
  
  public function down(){
     $this->dropTable(DaConfig::IMPORT_ESC_TABLE_NAME);
     $this->dropTable(DaConfig::IMPORT_TABLE_NAME);
  }
}