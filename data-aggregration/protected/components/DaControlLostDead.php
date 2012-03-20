<?php
  abstract class DaControlLostDead extends DaControl {
    /**
     * Table: tblAvLostDead, tblCvLostDead
     * LDdate should not be in year 1900
     * For a patient who has 2 status Lost and Dead. the lost status date should be before the dead date. 
     */

    
    /**
     * @throws DaInvalidControlException 
     */
    public function checkLDDate(){
      $year = DaConfig::getYear($this->row["LDdate"]);
      if($year == "1900"){
        throw new DaInvalidControlException("Invalid [LDDate]. [LDDate]={$year} should not be in 1900 ", $this->code);
      }
    }
  }
