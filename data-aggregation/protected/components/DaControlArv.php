<?php
 class DaControlArv extends DaControl{
   /**
    * Table: tblAvArv, tblCvArv, tblEvArv
    * ARV ( 3TC , ABC, AZT, d4T, ddI, EFV , IDV,  Kaletra(LPV/r), LPV,  NFV, NVP, RTV, SQV,. TDF) 
    */
  
   public function check($options=array()){
     return $this->checkARV();
   }
    
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
     $this->addError("Invalid [ARV] . [ARV] = ['{$arv}'] is not in '( " . implode( "," , $options["drugControls"]). ")'");
     return false ;
   }
 }