<?php
  class DaChecker {
    /**
     *
     * @param type $value
     * @return type 
     */
    public static function offYesNo($value){ 
      return strtolower(trim($value)) == "yes" ;
    }  
    /**
     *
     * @param string $date
     * @return boolean
     * @throws Exception 
     */
    public static function dateVisit($date){
      $year = DaTool::getYear($date);
      if($year == "1900" ){
         throw new Exception("Invalid [Date]='{$date}'. Year should not be 1900");
      }
      return true ;
    }
    
    public static function isEmpty($value){
      $isEmpty = trim($value);
      return $isEmpty == "";
    }
    
    /**
     *
     * @param string $date
     * @return boolean 
     */
    public static function checkDate($date){
      $year = DaTool::getYear($date);
      if($year == "1900" ){
        return false;
      }
      return true ;
    }
    
    /**
     *
     * @param string $dob
     * @param string $visit
     * @return boolean 
     */
    public static function under2Year($dob, $visit){
      $current = strtotime($visit);
      $born = strtotime($dob);
      $age = $current-$born ;
      if($age <= 3600*24*365*2 && $age > 0  )
        return true ;
      else
        return false;
    }
  }