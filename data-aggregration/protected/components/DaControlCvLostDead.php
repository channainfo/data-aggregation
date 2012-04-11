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
   public function check($options=array()) {
     return $this->checkLDDate() && $this->checkLostDead($options);
   }
   /**
    * Lost and dead need to check  checkLostDead
    * @param CDbConnection $db
    * @return boolean
    * @throws DaInvalidControlException 
    */
   public function checkLostDead($options){
     $valid = true ;
     $n = count($this->loadErrors($options));
     
     for($i=0; $i < $n ; $i++){
       if( $this->record["ClinicID"] == $this->errorRecords[$i]["ClinicID"]
           && $this->record["CID"] == $this->errorRecords[$i]["CID"] 
       //    && $this->record["status"] == $this->errorRecords[$i]["status"] 
       //    && $this->record["lddate"] == $this->errorRecords[$i]["lddate"]
       ) {
           $this->addError(" Invalid EvLostDead. [Date] = ['{$this->record["LDdate"]}'] , [Status] = [{$this->record['Status']}] ");
           $valid = false;
       }
     }
     return $valid;
   }
   /**
    *
    * @param CDbConnection $db
    * @return array
    */
   public function loadErrors($options){
     if($this->errorRecords !==false){
       return $this->errorRecords;
     }
     
     $db = $options["dbX"];
     $parentId = $options["parentId"] ;
     
     $sqlDeadFirst = " ( SELECT *  FROM tblcvlostdead as L1 WHERE L1.status = 'dead' AND cid='{$parentId}' ) ";
     $sqlLostLast  = " ( SELECT *  FROM tblcvlostdead as L2 WHERE L2.status = 'lost' AND cid='{$parentId}' ) ";
          
     $sql = " SELECT Lost.ClinicID as ClinicID , Lost.cid as CID  FROM "
          . "\n {$sqlLostLast} AS Lost , {$sqlDeadFirst} AS Dead "
          . "\n WHERE Lost.lddate > Dead.lddate "
          . "\n AND Lost.clinicid = Dead.clinicid  "
          . "\n AND Lost.cid = Dead.cid  "
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