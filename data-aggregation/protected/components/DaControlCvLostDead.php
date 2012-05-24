<?php
 class DaControlCvLostDead extends DaControlLostDead {
    public function tableName() {
      return "tblcvlostdead" ;
    }
    
    public function visitIdName() {
      return "cid" ;
    }
   
 }