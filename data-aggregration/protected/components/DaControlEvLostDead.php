<?php
 class DaControlEvLostDead extends DaControlLostDead {
   private $errorRecords = false;
   private $parentId = array();
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
           && $this->record["EID"] == $this->errorRecords[$i]["EID"] 
       //    && $this->record["status"] == $this->errorRecords[$i]["status"] 
       //    && $this->record["lddate"] == $this->errorRecords[$i]["lddate"]
       ) {
           $this->addError( " Invalid EvLostDead. [Date] = ['{$this->record["LDdate"]}'] , [Status] = [{$this->record['Status']}] " );
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
     
     if( $this->parentId == $options["parentId"] &&  !empty($this->errorRecords)){
       return $this->errorRecords;
     }
     $db = $options["dbX"];
     $this->parentId = $options["parentId"] ;
     
     $sqlDeadFirst = " ( SELECT *  FROM tblevlostdead as L1 WHERE L1.status = 'dead' AND eid='{$this->parentId}' ) ";
     $sqlLostLast  = " ( SELECT *  FROM tblevlostdead as L2 WHERE L2.status = 'lost' AND eid='{$this->parentId}' ) ";
          
     $sql = " SELECT Lost.clinicid as ClinicID, Lost.EID as EID  FROM "
          . "\n {$sqlLostLast} AS Lost , {$sqlDeadFirst} AS Dead "
          . "\n WHERE Lost.lddate > Dead.lddate "
          . "\n AND Lost.clinicid = Dead.clinicid  "
          . "\n AND Lost.eid = Dead.eid  "
          . "\n GROUP BY Lost.clinicid, Lost.eid "   
          ;
     $this->sql = $sql ;     
     $this->errorRecords = $db->createCommand($this->sql)->queryAll();
     return $this->errorRecords;
   }
   
   public function getErrQuery(){
     return $this->sql ;  
   }
   
 }