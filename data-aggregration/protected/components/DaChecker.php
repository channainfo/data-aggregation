<?php
  class DaChecker {
    
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
      $valid = true ;
      $art = trim($value);
      if(strtolower($art[0])== 'p'){
        $code =substr($art, 1);
        if(strlen($code) != 9){
          throw new Exception("Invalid [ART] number for child: [ART]= ['{$art}'] for child should have 10 characters in length ");
        }
      }
      else if(strlen($art) != 9){
        throw new Exception("Invalid [ART] number for adult: [ART]= ['{$art}'] should have 9 characters in length");
      }
      return $valid ;
    }
    
    public static function dateVisit($date){
      $year = DaTool::getYear($date);
      if($year == "1900" ){
         throw new Exception("Invalid [DateVisit]. Year of [DateVisit] should not be 1900");
      }
      return true ;
    }
    
    public static function under2Year($dob, $visit){
      if(trim($dob) == "")
        return true ;
      $current = strtotime($visit);
      $born = strtotime($dob);
      $age = $current-$born ;
      if($age > 3600*24*365*2 )
        return false ;
      else
        return true;
      
      
      
    }
    
  }