<?php
 class DaControlVArv extends DaControl{
   /**
    * Table: tblAvCrv, tblCvCrv
    * ARV ( 3TC , ABC, AZT, d4T, ddl, EFV , IDV,  Kaletra(LPV/r), LPV,  NFV, NVP, RTV, SQV,. TDF) 
    */
   
   public function check($options){
     $this->checkARV($options["drugControls"]);
   }
   
   /**
    *
    * @param array 
    * @return boolean
    * @throws DaInvalidControlException 
    */
   public function checkARV($drugControls){
     $n = count($drugControls);
     $arv =  trim($this->row["ARV"]);
     
     for($i=0; $i<$n; $i++){
       if($arv == $drugControls[$i]){
         return true;
       }
     }
     throw new DaInvalidControlException("Invalid [ARV] name. [ARV] {$arv} is not in ( ".implode(",",$drugControls))." )";
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