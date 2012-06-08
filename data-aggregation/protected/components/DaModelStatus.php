<?php
 class DaModelStatus extends DaActiveRecordModel {
   /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Backup the static model class
	 */
  
  const START = 0 ;
  const PENDING = 1;
  const FAILED = 2 ;
  const SUCCESS = 3 ;
  const INIT = 10 ;
  
  public function getStatusText(){
    $status = array( self::START => "start", self::PENDING => "pending" , self::FAILED => "failed", self::SUCCESS => "success", );
    if($this->status != self::INIT )
      return $status[(int)$this->status];
    
    return "" ;
  }
  
}
