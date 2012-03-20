<?php
 abstract class DaControlArv extends DaControl{
   /**
    * Table: tblAvCrv, tblCvCrv
    * ARV ( 3TC , ABC, AZT, d4T, ddl, EFV , IDV,  Kaletra(LPV/r), LPV,  NFV, NVP, RTV, SQV,. TDF) 
    */
    
   /**
    *
    * @param array 
    * @return boolean
    * @throws DaInvalidControlException 
    */
   public function checkARV($options){
     if( !isset($options["drugControls"]) || !is_array($options["drugControls"])){
       throw new Exception("Invalid parameters. parameters need to be an array with index [drugControls] and its value is an array of drug name ");
     }
     $n = count($options["drugControls"]);
     $arv =  trim($this->row["ARV"]);
     
     for($i=0; $i<$n; $i++){
       if($arv == $options["drugControls"][$i]){
         return true;
       }
     }
     throw new DaInvalidControlException("Invalid [ARV] name. [ARV] {$arv} is not in ( " . implode( "," , $options["drugControls"]) ) . " )";
   }
   /**
    *
    * @param array 
    * @return boolean
    * @throws DaInvalidControlException 
    */
   public function checkARVJustify($drugControls){
     $n = count($drugControls);
     $arv =  trim($this->row["ARV"]);
     for($i=0; $i<$n; $i++){
       if(strtolower($arv) == strtolower($drugControls[$i])){
         $this->row["ARV"] = $drugControls[$i];
         return true;
       }
     }
     throw new DaInvalidControlException("Invalid [ARV] name. [ARV] {$arv} is not in ( ".implode(",",$drugControls))." )";
   }
 }