<?php
 class DaControlAvLostDead extends DaControlLostDead {
   public $code = DaConfig::CTRL_EXCEPTION_AVLOSTDEAD;
   public $errorRecords = null;
   public $sql ;

   /**
    *
    * @throws DaInvalidControlException
    * @param array $option 
    */
   public function check($option=array()) {
     $this->checkLDDate();
   }
   /**
    *
    * @param CDbConnection $db
    * @return boolean
    * @throws DaInvalidControlException 
    */
   public function checkLostDead($db){
     
     for($i=0; $n = count($this->loadErrors($db)), $i < $n ; $i++){
       if( $this->record["clinicid"] == $this->errorRecords[$i]["clinicid"] &&
           $this->record["cid"] == $this->errorRecords[$i]["cid"] && 
           $this->record["status"] == $this->errorRecords[$i]["status"] &&  
           $this->record["lddate"] == $this->errorRecords[$i]["lddate"]) {
           throw new DaInvalidControlException(" Invalid AvLostDead. [Lost date]: ['{$this->record["lddate"]["LostDate"]}'] after [Dead] ['{$this->record["lddate"]["DeadDate"]}'] ");
       }
     }
     return true;
   }
   /**
    *
    * @param CDbConnection $db
    * @return array 
    */
   public function loadErrors($db){
     if($this->errorRecords !==false){
       return $this->errorRecords;
     }
     
     $sqlDeadFirst = " ( SELECT *  FROM tblavlostdead as L1 WHERE L1.status = 'dead' ) ";
     $sqlLostLast  = " ( SELECT *  FROM tblavlostdead as L2 WHERE L2.status = 'lost' ) ";
          
     $sql = " SELECT Lost.*, Lost.lddate as LostDate, Dead.lddate as DeadDate  FROM "
          . "\n {$sqlLostLast} AS Lost , {$sqlDeadFirst} AS Dead "
          . "\n WHERE Lost.lddate > Dead.lddate "
          . "\n AND Lost.clinicid = Dead.clinicid  "
          . "\n AND Lost.av_id = Dead.av_id  "
          . "\n GROUP BY Lost.clinicid, Lost.av_id "   
          ;
     $this->errorRecords = $db->createCommand($this->sql)->queryAll();
     $this->sql = $sql ; 
     return $this->errorRecords ;
     
   }
   
   public function getExeQuery(){
      return $this->sql ;
   }
     
 }