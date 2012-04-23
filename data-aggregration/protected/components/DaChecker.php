<?php
  class DaChecker {
    /**
     *
     * @param type $value
     * @return type 
     */
    public static function offYesNo($value){
      return strtolower($value) == "yes" ;
    }
    /**
     *
     * @param string $value
     * @return boolean
     * @throws Exception 
     */
    public static function artNum($value){
      $message = self::artError($value);
      if($message)
        throw new Exception($message);
      return true ;
    }
    
    public static function artError($value){
      $message = "" ;
      $art = trim($value);
      if(strtolower($art[0])== 'p'){
        $code =substr($art, 1);
        if(strlen($code) != 9){
          $message = "Invalid [ART] number for child: [ART]= ['{$art}'] for child should have 10 characters in length";
        }
      }
      else if(strlen($art) != 9){
        $message = "Invalid [ART] number for adult: [ART]= ['{$art}'] should have 9 characters in length";
      }
      return $message ;
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
      if($age > 3600*24*365*2 )
        return false ;
      else
        return true;
    }
  }