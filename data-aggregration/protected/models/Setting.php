<?php
  class Setting {
    public static function save($tables){
      $data = array() ;
      $file = dirname(__FILE__)."/../config/setting.php" ;
      foreach($tables as $tableName => $columns){
        $tmp = array();
        foreach($columns as $column => $value){
          $tmp[] = "'{$column}'";
        }
        $str = implode(",", $tmp);
        $data[] = "'{$tableName}' => array( $str ) ";
      }
      $str = implode(",\n\t", $data);
      $content = <<<EOT
<?php    
  return array(
     $str
);      
EOT;
      file_put_contents($file, $content);
      return $content;
    }
  }