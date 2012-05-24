<?php
class m120223_032951_add_columns_to_tbl_tables extends CDbMigration{
  
  
  public function up(){
    $this->createTable(DaConfig::IMPORT_TABLE_NAME, array(
        "id"          => "pk",
        "table_name"  => "string",
        "cols"        => "text",
        "created_at"  => "datetime",
        "priority"    => "int(4) default 0",
        "type"        => "string" ,
        "modified_at" => "datetime"
    ));
  }
  
  public function down(){
     $this->dropTable(DaConfig::IMPORT_TABLE_NAME);
  }
    
}