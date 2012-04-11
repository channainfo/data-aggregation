<?php
 class DaControlAvLostDead extends DaControlLostDead {
   public $errorRecords = null;
   public $sql ;

   /**
    *
    * @throws DaInvalidControlException
    * @param array $option 
    */
   public function check($options=array()) {
     return $this->checkLDDate() && $this->checkLostDead($options);
   }
   /**
    *
    * @param CDbConnection $db
    * @return boolean
    * @throws DaInvalidControlException 
    */
   public function checkLostDead($options){
     $valid = true;
     $n = count($this->loadErrors($options));
     
     for($i=0;  $i < $n ; $i++){
       if( $this->record["ClinicID"] == $this->errorRecords[$i]["ClinicID"] 
           && $this->record["AV_ID"] == $this->errorRecords[$i]["AV_ID"] 
          // && $this->record["status"] == $this->errorRecords[$i]["status"]  
          // && $this->record["lddate"] == $this->errorRecords[$i]["lddate"]
        ) {
           $this->addError(" Invalid EvLostDead. [Date] = ['{$this->record["LDdate"]}'] , [Status] = [{$this->record['Status']}] ");
           $valid = false;
       }
     }
     return $valid ;
   }
   /**
    *
    * @param array
    * @return array 
    */
   public function loadErrors($options){
     if($this->errorRecords !==false){
       return $this->errorRecords;
     }
     $db = $options["dbX"];
     $parentId = $options["parentId"] ;
     
     $sqlDeadFirst = " ( SELECT *  FROM tblavlostdead as L1 WHERE L1.status = 'dead' AND av_id = '{$parentId}' ) ";
     $sqlLostLast  = " ( SELECT *  FROM tblavlostdead as L2 WHERE L2.status = 'lost' AND av_id = '{$parentId}'  ) ";
          
     $sql = " SELECT Lost.av_id as AV_ID, Lost.clinicid as ClinicID  FROM "
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