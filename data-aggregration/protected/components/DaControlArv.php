<?php
 abstract class DaControlArv extends DaControl{
   /**
    * Table: tblAvArv, tblCvArv
    * ARV ( 3TC , ABC, AZT, d4T, ddl, EFV , IDV,  Kaletra(LPV/r), LPV,  NFV, NVP, RTV, SQV,. TDF) 
    */
    
   /**
    *
    * @param array 
    * @return boolean
    * @throws DaInvalidControlException 
    */
   public function checkARV(){
     $options = DaConfig::importConfig();
     
     $n = count($options["drugControls"]);
     $arv =  trim($this->record["ARV"]);
     
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
   public function checkARVJustify(){
     $options = DaConfig::importConfig();
     $n = count($options["drugControls"]);
     $n = count($options["drugControls"]);
     $arv =  trim($this->record["ARV"]);
     for($i=0; $i<$n; $i++){
       if(strtolower($arv) == strtolower($options["drugControls"][$i])){
         $this->record["ARV"] = $options["drugControls"][$i];
         return true;
       }
     }
     throw new DaInvalidControlException("Invalid [ARV] name. [ARV] {$arv} is not in ( ".implode(",",$options["drugControls"]))." )";
   }
 }