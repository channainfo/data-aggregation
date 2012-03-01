<?php
 interface IStatus{
   /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Backup the static model class
	 */
  const START = 0 ;
  const PENDING = 1;
  const FAILED = 2 ;
  const SUCCESS = 3 ;
  
  public function getStatusText();
  /**
   *
   * @return boolean 
   */
  public function restorable();
}
