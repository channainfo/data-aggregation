<?php
 class DaControlCvLostDead extends DaControlLostDead {
   public $code = DaConfig::CTRL_EXCEPTION_CVLOSTDEAD;
   private $errorRecords = false;
   private $sql ;

   /**
    *
    * @throws DaInvalidControlException
    * @param array $option 
    */
   public function check($option=array()) {
     $this->checkLDDate();
   }
   /**
    * Lost and dead need to check  checkLostDead
    * @param CDbConnection $db
    * @return boolean
    * @throws DaInvalidControlException 
    */
   public function checkLostDead($db){
     
     for($i=0; $n = count($this->loadErrors($db)), $i < $n ; $i++){
       if( $this->record["clinicid"] == $this->errorRecords[$i]["clinicid"] &&
           $this->record["av_id"] == $this->errorRecords[$i]["av_id"] && 
           $this->record["status"] == $this->errorRecords[$i]["status"] &&  
           $this->record["lddate"] == $this->errorRecords[$i]["lddate"]) {
           throw new DaInvalidControlException(" Invalid CvLostDead. [Lost date] = ['{$this->record["lddate"]["LostDate"]}'] after [Dead] = ['{$this->record["lddate"]["DeadDate"]}']  ");
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
     
     $sqlDeadFirst = " ( SELECT *  FROM tblcvlostdead as L1 WHERE L1.status = 'dead' ) ";
     $sqlLostLast  = " ( SELECT *  FROM tblcvlostdead as L2 WHERE L2.status = 'lost' ) ";
          
     $sql = " SELECT Lost.*, Lost.lddate as LostDate, Dead.lddate as DeadDate  FROM "
          . "\n {$sqlLostLast} AS Lost , {$sqlDeadFirst} AS Dead "
          . "\n WHERE Lost.lddate > Dead.lddate "
          . "\n AND Lost.clinicid = Dead.clinicid  "
          . "\n AND Lost.av_id = Dead.av_id  "
          . "\n GROUP BY Lost.clinicid, Lost.cid "   
          ;
     $this->sql = $sql ;     
     $this->errorRecords = $db->createCommand($this->sql)->queryAll();
     return $this->errorRecords;
   }
   
   public function getErrQuery(){
     return $this->sql ;  
   }
   
 }