<?php
  class DaFixtureTestCase  {
    private $table;
    private $cols;
    private $fixtures;
    private $records ;

        /**
     * @param String $table  
     */
    public function __construct($table, $cols, $fixtures) {
      $this->table = $table;
      $this->cols = $cols;
      $this->fixtures = $fixtures ;
      $this->records = array();
      $this->_createFixtures();
    } 
    public function getTable(){
      return $this->table;
    }
    /**
     * @param array $cols 
     */
    public function getCols(){
      return $this->cols;
    }
    /**
     * @param array $fixtures 
     */
    public function getFixtures(){
      return $this->fixtures ;
    } 
    
    public function getRecord(){
      return $this->records ;
    }


    private function _clearTable(){ 
     Yii::app()->db->createCommand("truncate {$this->table} ")->execute();
   }
     
   private function _loadTable(){
     $command = Yii::app()->db->createCommand("select * from {$this->table}");
     $this->records = $command->queryAll();
   }
   
   private function _createFixtures(){
     $this->_clearTable();
     
     
     $sql = DaSqlHelper::sqlFromTableCols($this->table, $this->cols);
     $command = Yii::app()->db->createCommand($sql);
     DaDbHelper::startIgnoringForeignKey(Yii::app()->db);
     
     foreach($this->fixtures as $data){
       for($i =0 ; $n = count($this->cols), $i<$n; $i++){
         $command->bindParam($this->cols[$i] , $data[$i], PDO::PARAM_STR ) ;
       }
       $command->execute();   
     }
     $this->_loadTable();
   }
  }