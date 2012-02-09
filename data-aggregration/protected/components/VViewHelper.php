<?php
 class VViewHelper {
   public static function dashboardIcon($url, $cssClass, $title, $description){
     $template = <<<EOT
     <div class="icon-wrapper" data-url="$url" > 
        <div class="icon-dashboard $cssClass">
          <h2 class="dashboard-title"> $title </h2>
          <p class="descritpion">
            $description
          </p>
        </div>
        <div class="clear"></div>
      </div> 
     
EOT;
     return $template;
     
   }
   
   public static function pageTitle($title){
     if(!empty($title)){
       return $title." - ".Yii::app()->name ;
     }
     return Yii::app()->name;
   }
   
   
   
 }
 
 
?>
