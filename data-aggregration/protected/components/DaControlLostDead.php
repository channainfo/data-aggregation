<?php
  class DaControlLostDead extends DaControl {
    /**
     * Table: tblAvLostDead, tblCvLostDead
     * LDdate should not be in year 1900
     * For a patient who has 2 status Lost and Dead. the lost status date should be before the dead date. 
     */
    
    /**
     * @throws DaInvalidControlException 
     */
    public function check(){
      $this->checkLDDate();
    }
    
    /**
     * @throws DaInvalidControlException 
     */
    public function checkLDDate(){
      $date = trim($this->row["LDdate"]);
      $year = substr($date, 0, 4);
      if($year == "1900"){
        throw new DaInvalidControlException("Invalid [LDDate]. [LDDate]={$year} should not be in 1900 ");
      }
    }
    
  }
