<?php
 class DaControlEvLostDead extends DaControlLostDead {
    public function tableName() {
      return "tblevlostdead" ;
    }
    public function visitIdName() {
      return "eid" ;
    }
 }